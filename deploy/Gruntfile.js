'use strict';

module.exports = function (grunt) {

    // Configurable paths for the application
    var webPath = '../web/',
        distPath = '../build/',
        staticPathBase = '../web/base/',
        staticPathCustomers = '../web/customers/',
        staticPathPlugins = '../web/plugin/',
        paths = {
            'web': webPath,
            'dist': distPath,
            'base': staticPathBase,
            'customers': staticPathCustomers,
            'plugins': staticPathPlugins,
            // 'customerCss': staticPathCustomers + 'css/',
            // 'customerJs': staticPathCustomers + 'js/',
            // 'customerLess': staticPathCustomers + 'less/',
            // 'pluginJsSite': staticPathPlugins + 'site/js/',
            // 'pluginJsToolbox': staticPathPlugins + 'toolbox/js/',
            // 'pluginJsCommon': staticPathPlugins + 'common/js/'
        };

    // Load grunt tasks automatically
    require('load-grunt-tasks')(grunt);

    // Time how long tasks take. Can help when optimizing build times
    require('time-grunt')(grunt);

    // Define the configuration for all the tasks
    grunt.initConfig({

        // Project settings
        paths: paths,

        // Watches files for changes and runs tasks based on the changed files
        watch: {
            js: {
                files: ['<%= paths.customers %>{,*/}**/*.js', '<%= paths.plugins %>{,*/}**/*.js'],
                tasks: ['newer:jshint:all']
            },
            less: {
                files: ['<%= paths.customers %>/{,*/}/less/*.less'],
                tasks: ['newer:less:theme']
            },
            gruntfile: {
                files: ['Gruntfile.js']
            }
        },

        // Compiles LESS to CSS
        less: {
            theme: {
                options: {
                    paths: ['<%= paths.web %>']
                },
                files: [{
                    expand: true,
                    cwd: '<%= paths.customers %>',
                    dest: '<%= paths.customers %>',
                    src: '{,*/}less/theme.less',
                    rename  : function (dest, src) {
                        var customerName    = src.substring(0, src.indexOf('/'));
                        var filename  = src.substring(src.lastIndexOf('/') + 1, src.length);
                        filename  = filename.substring(0, filename.lastIndexOf('.'));
                        // grunt.log.writeln('[' + customerName + '] creating css: ' + dest + customerName + '/css/' + filename + '.css');
                        return dest + customerName + '/css/' + filename + '.css';
                    }
                }]
            }
        },

        // Checks JS for common mistakes and style guide
        jshint: {
            options: {
                jshintrc: '.jshintrc',
                reporter: require('jshint-stylish')
            },
            all: {
                src: [
                    'Gruntfile.js',
                    '<%= paths.customers %>{,*/}**/*.js',
                    '<%= paths.plugins %>{,*/}**/*.js'
                ]
            }
        },

        // Copies remaining files to places other tasks can use
        copy: {
            dist: {
                files: [{
                    expand: true,
                    cwd: '<%= paths.web %>',
                    dest: '<%= paths.dist %>',
                    src: '**/*'
                }]
            }
        },

        // Concats and uglifies all JS files that are under control of RequireJS
        requirejs: {
            compile: {
                options: {
                    // appDir: paths.dist,
                    baseUrl: paths.web,
                    mainConfigFile: paths.base + 'js/loader.js',
                    dir: paths.dist,
                    // name: 'app',
                    findNestedDependencies: true,
                    optimizeAllPluginResources: true,
                    optimize: 'none',
                    uglify2: {
                        output: {
                            'quote_keys': true
                        }
                    },
                    optimizeCss: "standard",
                    // optimize: "none",
                    // inlining ftw
                    inlineText: true,
                    pragmasOnSave: {
                        //removes Handlebars.Parser code (used to compile template strings) set
                        //it to `false` if you need to parse template strings even after build
                        excludeHbsParser : true,
                        // kills the entire plugin set once it's built.
                        excludeHbs: true,
                        // removes i18n precompiler, handlebars and json2
                        excludeAfterBuild: true
                    },
                    keepBuildDir: true,
                    modules: [
                        {
                            name: 'customers/pb.com.ua/js/router'
                        }
                    ]
                }
            }
        }
    });

    grunt.registerTask('default', [
        // 'jshint:all',
        'less:all'
    ]);

    grunt.registerTask('deploy', 'Deployment', function () {
        grunt.log.writeln('Running production build...');
        grunt.task.run([/*'jshint', */'requirejs', 'less'/*, 'copy:dist'*/]);
    });
};