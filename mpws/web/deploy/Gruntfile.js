module.exports = function(grunt) {

    var version = grunt.option('mv') || 'atlantis';
    var customer = grunt.option('mc') || '';
    // var mode = grunt.option('mode') || 'all';
    // var allModes = ['site', 'toolbox'];

    if (!customer)
        throw "Empty customer name";

    function _getBuildConfig () {

        var _baseConfigLess = {};
        var _baseConfigWatch = {};

        var _getPaths = function () {
            var _dirAppBuild = 'build/';
            var _dirAppJs = 'static/js';
            var _dirAppHbs = 'static/hbs';
            var _dirAppLess = 'static/less';
            var _dirAppCss = 'static/css';
            // var _dirCommonLess = 'static/common/less';

            var _dirDefaultLess = 'static/less';

            var _srcLessCustomer = '../customer/' + customer + '/' + _dirAppLess + '/';
            // var _srcLessCustomerCommon = '../customer/' + customer + '/' + _dirCommonLess + '/';
            var _srcLessDefault = '../default/' + version + '/' + _dirDefaultLess;

            var _devLessCustomer = '../customer/' + customer + '/' + _dirAppCss + '/';
            var _buildLessCustomer = '../customer/' + customer + '/' + _dirAppBuild + '/' + _dirAppLess + '/';

            return {
                dirAppBuild : _dirAppBuild,
                dirAppJs : _dirAppJs,
                dirAppHbs : _dirAppHbs,
                dirAppLess : _dirAppLess,
                dirAppCss : _dirAppCss,
                // dirCommonLess : _dirCommonLess,
                dirDefaultLess : _dirDefaultLess,
                srcLessCustomer : _srcLessCustomer,
                // srcLessCustomerCommon : _srcLessCustomerCommon,
                srcLessDefault : _srcLessDefault,
                devLessCustomer : _devLessCustomer,
                buildLessCustomer : _buildLessCustomer
            }
        }

        var _getConfigLess = function () {

            var _paths = _getPaths();

            _baseConfigLess['development'] = {
                options: {
                    paths: [
                        _paths.srcLessCustomer,
                        _paths.srcLessDefault
                    ]
                },
                files: [
                    // target name
                    {
                        // no need for files, the config below should work
                        expand: true,
                        cwd: _paths.srcLessCustomer,
                        src: ["**/*", "*.less"],
                        dest: _paths.devLessCustomer,
                        ext: '.css'
                    }
                ]
            };

            _baseConfigLess['production'] = {
                options: {
                    paths: [
                        _paths.srcLessCustomer,
                        _paths.srcLessDefault
                    ],
                    cleancss: true
                },
                files: [
                    // target name
                    {
                        // no need for files, the config below should work
                        expand: true,
                        cwd: _paths.srcLessCustomer,
                        src: ["**/*", "*.less"],
                        dest: _paths.buildLessCustomer,
                        ext: '.css'
                    }
                ]
            };

        }

        var _getConfigWatch = function () {
            var _paths = _getPaths();

            _baseConfigWatch['styles'] = {
                // Which files to watch (all .less files recursively in the less directory)
                files: [
                    _paths.srcLessCustomer + '*.less',
                    _paths.srcLessCustomer + '**/*.less',
                    _paths.srcLessDefault + '*.less',
                    _paths.srcLessDefault + '**/*.less'
                ],
                tasks: ['less:development'],
                options: {
                    nospawn: true
                }
            };
        }

        var _getInBundle = function () {
            _getConfigLess();
            _getConfigWatch();
        }

        // if (mode === 'all')
        //     for (var key in allModes) {
        //         _getInBundle(allModes[key])
        //     }
        // else
        _getInBundle();

        return {
            less: _baseConfigLess,
            watch: _baseConfigWatch
        }
    }

    grunt.initConfig(_getBuildConfig());



    // grunt.initConfig({
    // });

    // grunt.loadNpmTasks('grunt-contrib-uglify');
    // grunt.loadNpmTasks('grunt-contrib-jshint');
    // grunt.loadNpmTasks('grunt-contrib-qunit');
    grunt.loadNpmTasks('grunt-contrib-watch');
    // grunt.loadNpmTasks('grunt-contrib-concat');
    grunt.loadNpmTasks('grunt-contrib-less');

    grunt.registerTask('style_watch', ['less:development*', 'watch']);

    // grunt.registerTask('default', ['jshint', 'qunit', 'concat', 'uglify']);

};