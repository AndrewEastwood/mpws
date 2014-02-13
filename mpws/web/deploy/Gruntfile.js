module.exports = function(grunt) {

    var version = grunt.option('mv') || 'atlantis';
    var customer = grunt.option('mc') || '';

    if (!customer)
        throw "Empty customer name";

    var _dirBuild = 'build';
    var _dirJs = 'static/js';
    var _dirHbs = 'static/hbs';
    var _dirLess = 'static/less';
    var _dirCss = 'static/css';

    var _srcLessCustomer = '../customer/' + customer + '/' + _dirLess + '/';
    var _srcLessDefault = '../default/' + version + '/' + _dirLess + '/';

    var _devLessCustomer = '../customer/' + customer + '/' + _dirCss + '/';
    var _buildLessCustomer = '../customer/' + customer + '/' + _dirBuild + '/' + _dirLess + '/';

    grunt.initConfig({
        less: {
            development: {
                options: {
                    paths: [
                        _srcLessDefault
                    ]
                },
                files: [
                    // target name
                    {
                        // no need for files, the config below should work
                        expand: true,
                        cwd: _srcLessCustomer,
                        src: ["*.less"],
                        dest: _devLessCustomer,
                        ext: '.css'
                    }
                ]
            },
            production: {
                options: {
                    paths: [
                        _srcLessDefault
                    ],
                    cleancss: true
                },
                files: [
                    // target name
                    {
                        // no need for files, the config below should work
                        expand: true,
                        cwd: _srcLessCustomer,
                        src: ["*.less"],
                        dest: _buildLessCustomer,
                        ext: '.css'
                    }
                ]
            }
        },
        watch: {
            styles: {
                // Which files to watch (all .less files recursively in the less directory)
                files: [_srcLessCustomer + '*.less', _srcLessDefault + '*.less'],
                tasks: ['less:development'],
                options: {
                    nospawn: true
                }
            }
        }
    });

    // grunt.loadNpmTasks('grunt-contrib-uglify');
    // grunt.loadNpmTasks('grunt-contrib-jshint');
    // grunt.loadNpmTasks('grunt-contrib-qunit');
    grunt.loadNpmTasks('grunt-contrib-watch');
    // grunt.loadNpmTasks('grunt-contrib-concat');
    grunt.loadNpmTasks('grunt-contrib-less');

    grunt.registerTask('style_watch', ['less:development', 'watch']);

    // grunt.registerTask('default', ['jshint', 'qunit', 'concat', 'uglify']);

};