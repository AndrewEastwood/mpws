<?php
	/**
     * ChianQueryBuilder
     * 
     * Copyright (c) 2010 BarsMaster
     * e-mail: barsmaster@gmail.com, arthur.borisow@gmail.com
     * 
     * Permission is hereby granted, free of charge, to any person obtaining a copy
     * of this software and associated documentation files (the "Software"), to deal
     * in the Software without restriction, including without limitation the rights
     * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
     * copies of the Software, and to permit persons to whom the Software is
     * furnished to do so, subject to the following conditions:
     * 
     * The above copyright notice and this permission notice shall be included in
     * all copies or substantial portions of the Software.
     * 
     * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
     * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
     * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
     * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
     * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
     * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
     * THE SOFTWARE.
     * 
     * @author BarsMaster
     * @copyright 2010
     * @version 1.1.1
     * @access public
     */
    class ChainQueryBuilder {
        private $_operation = '';
        private $_fields = array();
        private $_values = array();
        private $_tables = array();
        
        private $_join = '';
        private $_using = '';
        private $_on = array();

        private $_where = array();
        private $_limit = 0;
        private $_offset = 0;

        private $_orderBy = array();
        private $_order = 'ASC';
        private $_groupBy = array();

        public function orderBy($orderBy) {
            $args = $this->_getArgs(func_get_args());
            $this->_orderBy = $args;
            return $this;
        }

        public function order($order) {
            $order = strtoupper($order);
            if (in_array($order, array('ASC', 'DESC'))) {
                $this->_order = $order;
            }
            return $this;
        }

        public function groupBy($groupBy) {
            $args = $this->_getArgs(func_get_args());
            $this->_groupBy = $args;
            return $this;
        }

        public function offset($offset = 0) {
            $offset = (int)abs($offset);
            if ($offset) {
                $this->_offset = $offset;
            }
            return $this;
        }
        
        public function join($table) {
            $this->_join = 'JOIN ' . $table;
            return $this;
        }
        
        public function leftJoin($table) {
            $this->_join = 'LEFT JOIN ' . $table;
            return $this;
        }
        
        public function rightJoin($table) {
            $this->_join = 'RIGHT JOIN ' . $table;
            return $this;
        }
        
        public function using($field) {
            $this->_using = $field;
            return $this;
        }
        
        public function on($c1, $operand, $c2) {
            return $this->_addWhereOn($c1, $operand, $c2, '', 'on');
        }
        
        public function andOn($c1, $operand, $c2) {
            return $this->_addWhereOn($c1, $operand, $c2, 'AND', 'on');
        }
        
        public function orOn($c1, $operand, $c2) {
            return $this->_addWhereOn($c1, $operand, $c2, 'AND', 'on');
        }

        public function limit($limit = 0) {
            $limit = (int)abs($limit);
            $this->_limit = $limit;
            return $this;
        }

        public function select($fields) {
            $this->_setOperation('select');
            $args = $this->_getArgs(func_get_args());
            foreach ($args as $arg) {
                $this->addField($arg);
            }
            return $this;
        }

        public function from($tables) {
            if ($this->_operation != 'SELECT') {
                throw new Exception('Only SELECT operators.');
            }
            $this->_tables = $this->_getArgs(func_get_args());
            return $this;
        }

        public function where($cond1, $operand, $cond2) {
            return $this->_addWhereOn($cond1, $operand, $cond2, '', 'where');
        }

        public function andWhere($cond1, $operand, $cond2) {
            return $this->_addWhereOn($cond1, $operand, $cond2, 'AND', 'where');
        }

        public function orWhere($cond1, $operand, $cond2) {
            return $this->_addWhereOn($cond1, $operand, $cond2, 'OR', 'where');
        }

        private function _addWhereOn($cond1, $operand, $cond2, $type, $property) {
            $operand = strtoupper($operand);
            if (!in_array($operand, array('=', '>', '<', '<>', '!=', '<=', '>=', 'LIKE', 'IN'))) {
                throw new Exception('Unsupported operand:' . $operand);
            }
            $this->{'_' . $property}[] = array(
                                    'cond1' => $cond1,
                                    'operand' => $operand,
                                    'cond2' => $cond2,
                                    'type' => $type
                                );
            return $this;
        }

        public function addField($field) {
            if (!in_array($field, $this->_fields)) {
                $this->_fields[] = $field;
            }
            return $this;
        }

        protected function _sanitizeValue($val, $search = false) {
            if (!is_numeric($val)) {
                $val = '\'' . $val . '\'';
            }
            return $val;
        }

        public function insertInto($table) {
            $this->_setOperation('insert');
            $this->_tables[] = $table;
            return $this;
        }

        public function fields($fields) {
            if (!in_array($this->_operation, array('INSERT', 'UPDATE'))) {
                throw new Exception('Only INSERT and Update operations.');
            }
            $args = $this->_getArgs(func_get_args());
            $this->_fields = $args;
            return $this;
        }

        public function values($values) {
            if (!in_array($this->_operation, array('INSERT', 'UPDATE'))) {
                throw new Exception('Only INSERT and Update operations.');
            }
            $args = $this->_getArgs(func_get_args());
            if (count($args) != count($this->_fields)) {
                throw new Exception('Number of values has to be equal to the number of fields.');
            }
            if ($this->_operation == 'INSERT') {
                $this->_values[] = $args;
            } elseif ($this->_operation == 'UPDATE') {
                $this->_values = $args;
            }
            return $this;
        }

        public function deleteFrom($table) {
            $this->_setOperation('delete');
            $args = $this->_getArgs(func_get_args());
            $this->_tables = $args;
            return $this;
        }

        public function update($table) {
            $this->_setOperation('update');
            $this->_tables = array($table);
            return $this;
        }

        public function set($field) {
            $args = func_get_args();
            if (count($args) == 2) {
                $args = array($args[0] => $args[1]);
            } else {
                $args = $this->_getArgs(func_get_args());

            }
            foreach ($args as $field => $val) {
                if (!in_array($field, $this->_fields)) {
                    $this->_fields[] = $field;
                    $this->_values[] = $val;
                }
            }

            return $this;
        }

        private function _setOperation($operation) {
            if ($this->_operation) {
                throw new Exception('Can\'t modify the operator.');
            } elseif (!in_array($operation, array('select', 'insert', 'delete', 'update'))) {
                throw new Exception('Unsupported operator:' . strtoupper($operation));
            } else {
                $operation = strtoupper($operation);
                $this->_operation = $operation;
            }
        }

        private function _getArgs($args) {
            $argsCnt = count($args);
            if (!$argsCnt) {
                return array();
            }

            if ($argsCnt == 1) {
                if (!is_array($args[0])) {
                    return array($args[0]);
                }
                return $args[0];
            } else {
                $return = array();

                foreach ($args as $arg) {
                    $return[] = $arg;
                }

                return $return;
            }
        }

        public function build() {
            $statement = array();
            $this->_buildOperator($statement);
            $op = '_build' . $this->_operation;
            $this->$op($statement);
            
            $this->_buildJoin($statement);

            $this->_buildWhereOn($statement, 'where');

            $this->_buildGroupBy($statement);

            $this->_buildOrderBy($statement);

            $this->_buildLimit($statement);

            return implode(' ', $statement);
        }
        
        private function _buildJoin(&$statement) {
            if (!$this->_join) {
                return;
            }
            $statement[] = $this->_join;
            if ($this->_using) {
                $statement[] = 'USING(' . $this->_using . ')';
            }
            $this->_buildWhereOn($statement, 'on');
        }
        private function _buildUpdate(&$statement) {
            $statement[] = implode(', ', $this->_tables);
            $statement[] = 'SET';
            $set = array();
            foreach($this->_fields as $k => $f) {
                $set[] = $f . ' = ' . $this->_sanitizeValue($this->_values[$k]);
            }
            $statement[] = implode(', ', $set);
        }
        private function _buildDELETE(&$statement) {
            $statement[] = 'FROM ' . implode(', ', $this->_tables);
        }
        private function _buildSELECT(&$statement) {
            $statement[] = implode(', ', $this->_fields);
            $statement[] = 'FROM ' . implode(', ', $this->_tables);
        }

        private function _buildINSERT(&$statement) {
            $statement[] = 'INTO';
            $statement[] = implode(', ', $this->_tables);
            $this->_buildINSERTFields($statement);
            $statement[] = 'VALUES';
            $this->_buildINSERTValues($statement);
        }

        private function _buildINSERTFields(&$statement) {
            $statement[] = '(' . implode(', ', $this->_fields) . ')';
        }

        private function _buildINSERTValues(&$statement) {
            $values = array();
            foreach ($this->_values as $val) {
                foreach ($val as & $v) {
                    $v = $this->_sanitizeValue($v);
                }
                $values[] = '(' . implode(', ', $val) . ')';
            }
            $statement[] = implode(', ', $values);
        }

        private function _buildOperator(&$statement) {
            $statement[] = $this->_operation;
        }

        private function _buildWhereOn(&$statement, $type) {
            if (!in_array($this->_operation, array('UPDATE', 'DELETE', 'SELECT'))) {
                return;
            }
            if (count($this->{'_' . strtolower($type)})) {
                $statement[] = strtoupper($type);
                foreach ($this->{'_' . strtolower($type)} as $where) {
                    $tmp = array($where['type'], $where['cond1'], $where['operand']);
                    if ($where['operand'] != 'IN') {
                        if ($type == 'where') {
                            $tmp[] = $this->_sanitizeValue($where['cond2'], $where['operand'] == 'LIKE');
                        } else {
                            $tmp[] = $where['cond2'];
                        }
                    } else {
                        $ins = array();
                        if (!is_array($where['cond2'])) {
                            $ins = array($where['cond2']);
                        } else {
                            foreach($where['cond2'] as $c2) {
                                $ins[] = $this->_sanitizeValue($c2, false);
                            }
                        }
                        $tmp[2] = $tmp[2] . '(' . implode(', ', $ins) . ')';
                    }
                    $statement[] = implode(' ', $tmp);
                }
            }
        }

        private function _buildGroupBy(&$statement) {
            if ($this->_operation != 'SELECT') {
                return;
            }
            if (count($this->_groupBy)) {
                $statement[] = 'GROUP BY';
                $gbs = array();
                foreach ($this->_groupBy as $gb) {
                    $gbs[] = $gb;
                }
                $statement[] = implode(', ', $gbs);
            }
        }

        private function _buildOrderBy(&$statement) {
            if ($this->_operation != 'SELECT') {
                return;
            }
            if (count($this->_orderBy)) {
                $statement[] = 'ORDER BY';
                $obs = array();
                foreach ($this->_orderBy as $ob) {
                    $obs[] = $ob;
                }
                $statement[] = implode(', ', $obs);
                $statement[] = $this->_order;
            }
        }

        private function _buildLimit(&$statement) {
            if ($this->_offset > 0 && $this->_limit > 0) {
                $statement[] = 'LIMIT ' . $this->_offset . ', ' . $this->_limit;
            } elseif ($this->_offset > 0) {
                $statement[] = 'OFFSET ' . $this->_offset;
            } elseif ($this->_limit > 0) {
                $statement[] = 'LIMIT ' . $this->_limit;
            }
        }
    }
    
?>