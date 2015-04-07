'use strict';

module.exports = function (grunt) {

    // Configurable paths for the application
    var appDir = '../',
        staticPath = '../static_/',
        jsBuildPath = '../_dist_/static_/',
        distPath = '../_dist_/',
        staticPathBase = '../static_/base/',
        staticPathCustomers = '../static_/customers/',
        staticPathPlugins = '../static_/plugin/',
        modulesBase = [
            'sandbox',
            'jquery',
            'underscore',
            'backbone',
            'auth',
            'jsurl',
            'cachejs',
            'asyncjs',
            'handlebars-helpers',
            'handlebars-partials',
            // localizations
            'vendors/moment/locale/uk',
            'vendors/select2/select2_locale_uk',
            'bootstrap'
        ],
        customers = ['toolbox', 'leogroup.com.ua'],
        plugins = ['system', 'shop'],
        paths = {
            'app': appDir,
            'static': staticPath,
            'jsbuild': jsBuildPath,
            'dist': distPath,
            'base': staticPathBase,
            'customers': staticPathCustomers,
            'plugins': staticPathPlugins
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
                    paths: ['<%= paths.static %>']
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
                    cwd: '<%= paths.app %>',
                    dest: '<%= paths.dist %>',
                    src: ['engine/**/*', 'app.php', '.htaccess', 'robots.txt']
                }]
            }
        },

        // Concats and uglifies all JS files that are under control of RequireJS
        requirejs: {
            compile: {
                options: {
                    baseUrl: paths.static,
                    mainConfigFile: paths.base + 'js/loader.js',
                    dir: paths.jsbuild,
                    findNestedDependencies: false,
                    optimize: 'none',
                    // optimize: 'uglify2',
                    skipDirOptimize: true,
                    modules: getModules(),
                    optimizeCss: 'none'
                }
            }
        }
    });

    function getModules () {
        var mods = [];

        mods.push({
            name: 'base/js/app',
            include: modulesBase
        });

        for (var i = 0; i < customers.length; i++) {
            mods.push({
                name: 'customers/' + customers[i] + '/js/router',
                exclude: modulesBase
            });
        }
        for (var i = 0; i < plugins.length; i++) {
            mods.push({
                name: 'plugins/' + plugins[i] + '/toolbox/js/router',
                exclude: modulesBase
            });
            mods.push({
                name: 'plugins/' + plugins[i] + '/site/js/router',
                exclude: modulesBase
            });
        }
        return mods;
    }

    grunt.registerTask('default', [
        // 'jshint:all',
        'less:all'
    ]);

    grunt.registerTask('deploy', 'Deployment', function () {
        grunt.log.writeln('Running deployment...');
        grunt.task.run([/*'jshint', */'requirejs', 'less', 'copy:dist']);
        grunt.file.write(distPath + 'version.txt', Date.now());
        grunt.file.write(distPath + 'env.txt', 'production');
    });
};