'use strict';

module.exports = function (grunt) {

    // Configurable paths for the application
    var webPath = '../web/',
        distPath = '../web/build/',
        staticPathCustomers = '../web/customers/**/static/',
        staticPathPlugins = '../web/plugin/**/static/',
        paths = {
            'web': webPath,
            'dist': distPath,
            'baseLess': webPath + 'base/atlantis/static/less/',
            'customerCss': staticPathCustomers + 'css/',
            'customerJs': staticPathCustomers + 'js/',
            'customerLess': staticPathCustomers + 'less/',
            'pluginJsSite': staticPathPlugins + 'site/js/',
            'pluginJsToolbox': staticPathPlugins + 'toolbox/js/',
            'pluginJsCommon': staticPathPlugins + 'common/js/'
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
                files: ['<%= paths.customerJs %>{,*/}*.js'],
                tasks: ['newer:jshint:all']
            },
            less: {
                files: ['<%= paths.assets %>less/{,*/}*.less'],
                tasks: ['less:all']
            },
            gruntfile: {
                files: ['Gruntfile.js']
            }
        },

        // Compiles LESS to CSS
        less: {
            all: {
                options: {
                    paths: ['<%= paths.customerLess %>', '<%= paths.baseLess %>']
                    //sourceMap: true,
                    //sourceMapURL: 'main.css.map',
                    //sourceMapRootpath: '../../'
                },
                files: [{
                    expand: true,
                    cwd: '<%= paths.web %>',
                    dest: '<%= paths.dist %>',
                    src: '**/*'
                    rename  : function (dest, src) {
                      var folder    = src.substring(0, src.lastIndexOf('/'));
                      var filename  = src.substring(src.lastIndexOf('/'), src.length);

                      filename  = filename.substring(0, filename.lastIndexOf('.'));

                      return dest + folder + filename + '.min.js';
                    }
                    '<%= paths.customerCss %>theme.css': '<%= paths.customerLess %>theme.less'
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
                    '<%= paths.customerJs %>{,*/}*.js',
                    '<%= paths.pluginJsCommon %>{,*/}*.js',
                    '<%= paths.pluginJsToolbox %>{,*/}*.js',
                    '<%= paths.pluginJsSite %>{,*/}*.js',
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
                    baseUrl: paths.customerJs,
                    mainConfigFile: paths.app +'loader.js',
                    name: 'app',
                    out: paths.customerJs + 'js/router.min.js',
                    include: ['loader.js'],
                    findNestedDependencies: true,
                    optimizeAllPluginResources: false,
                    optimize: 'uglify2',
                    uglify2: {
                        output: {
                            'quote_keys': true
                        }
                    },
                    keepBuildDir: true
                }
            }
        }
    });

    grunt.registerTask('default', [
        // 'jshint:all',
        'less:all',
        'copy:dist'
    ]);

    grunt.registerTask('deploy', 'Deployment', function () {
        grunt.log.writeln('Running production build...');
        grunt.task.run([/*'jshint', */'requirejs', 'less', 'copy:dist']);
    });
};