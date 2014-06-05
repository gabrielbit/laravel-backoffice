module.exports = function(grunt) {
	//Initializing the configuration object
	grunt.initConfig({

		// Task configuration
		less: {
			development: {
				options: {
					compress: true  //minifying the result
				},
				files: {
					//compiling frontend.less into frontend.css
					"./public/css/backoffice.css":"./src/assets/less/backoffice.less",
					"./public/css/custom.css":"./src/assets/less/custom.less"
				}
			}
		},
		concat: {
			backoffice: {
				src: [
					"./vendor/digbang/backoffice-template/template/js/jquery-1.10.2.min.js",
					"./vendor/digbang/backoffice-template/template/js/jquery-migrate-1.2.1.min.js",
					"./vendor/digbang/backoffice-template/template/js/jquery-ui-1.10.3.min.js",
					"./vendor/digbang/backoffice-template/template/js/bootstrap.min.js",
					"./vendor/digbang/backoffice-template/template/js/modernizr.min.js",
					"./vendor/digbang/backoffice-template/template/js/jquery.sparkline.min.js",
					"./vendor/digbang/backoffice-template/template/js/toggles.min.js",
					"./vendor/digbang/backoffice-template/template/js/retina.min.js",
					"./vendor/digbang/backoffice-template/template/js/jquery.cookies.js",
					"./vendor/digbang/backoffice-template/template/js/jquery.autogrow-textarea.js",
					"./vendor/digbang/backoffice-template/template/js/bootstrap-fileupload.min.js",
					"./vendor/digbang/backoffice-template/template/js/bootstrap-timepicker.min.js",
					"./vendor/digbang/backoffice-template/template/js/jquery.maskedinput.min.js",
					"./vendor/digbang/backoffice-template/template/js/jquery.tagsinput.min.js",
					"./vendor/digbang/backoffice-template/template/js/jquery.mousewheel.js",
					"./vendor/digbang/backoffice-template/template/js/jquery.gritter.min.js",
					"./vendor/digbang/backoffice-template/template/js/dropzone.min.js",
					"./vendor/digbang/backoffice-template/template/js/colorpicker.js",
					"./vendor/digbang/backoffice-template/template/js/jquery.datatables.min.js",
					"./vendor/digbang/backoffice-template/template/js/jquery.validate.min.js",
					"./vendor/digbang/backoffice-template/template/js/chosen.jquery.min.js",
					"./vendor/digbang/backoffice-template/template/js/custom.js",
					"./vendor/digbang/backoffice-template/template/js/jquery.prettyPhoto.js",
					"./vendor/digbang/backoffice-template/template/js/wysihtml5-0.3.0.min.js",
					"./vendor/digbang/backoffice-template/template/js/bootstrap-wysihtml5.js",
					"./bower_components/jquery-form/jquery.form.js",
					"./bower_components/bootbox/bootbox.js",
					"./bower_components/quicksearch/src/jquery.quicksearch.js",
					"./bower_components/multiselect/js/jquery.multi-select.js",
					"./src/assets/js/backoffice.js"
				],
				dest: './public/js/backoffice.js',
				options: {
					separator: ';'
				}
			},
			"ie8compat": {
				src: [
					"./vendor/digbang/backoffice-template/template/js/html5shiv.js",
					"./vendor/digbang/backoffice-template/template/js/respond.min.js",
				],
				dest: './public/js/ie8compat.js',
				options: {
					separator: ';'
				}
			}
		},
		copy: {
			backoffice: {
				files: [
					{
						cwd: './vendor/digbang/backoffice-template/template/',
						src: ['images/**', 'fonts/**'],
						dest: './public',
						expand: true
					}
				]
			}
		},
		/*uglify: {
		 options: {
		 mangle: false  // Use if you want the names of your functions and variables unchanged
		 },
		 backoffice: {
		 files: {
		 './public/assets/javascript/backend.js': './public/assets/javascript/backend.js',
		 }
		 },
		 },*/
		watch: {
			less: {
				files: ['./app/assets/less/*.less'],  //watched files
				tasks: ['less'],                          //tasks to run
				options: {
					livereload: true                        //reloads the browser
				}
			}
		}
	});

	// Plugin loading
	grunt.loadNpmTasks('grunt-contrib-concat');
	grunt.loadNpmTasks('grunt-contrib-watch');
	grunt.loadNpmTasks('grunt-contrib-less');
	// grunt.loadNpmTasks('grunt-contrib-uglify');
	grunt.loadNpmTasks('grunt-contrib-copy');

	// Task definition
	grunt.registerTask('default', ['less', 'concat', 'copy']);

};