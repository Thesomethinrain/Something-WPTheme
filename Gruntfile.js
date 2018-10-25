module.exports = function(grunt) {

grunt.initConfig({
	pkg: grunt.file.readJSON('package.json'),


	// JsHint your javascript
	jshint : {
		src: {
			src: ['js/*.js', '!js/vendor/*.js']
		},
		options : {
			browser: true,
			curly: false,
			eqeqeq: false,
			eqnull: true,
			expr: true,
			immed: true,
			newcap: true,
			noarg: true,
			smarttabs: true,
			sub: true,
			undef: false
		}
	},

	concat: {
	// 2. la configuration pour la concaténation va ici.
		dev: {
			src: [
			'src/js/*.js', '!src/js/vendors/*.js' //'src/js/vendor/**/*.js', 'src/js/main.js'
			],
			dest: 'dist/js/global.js'
		}
	},

	uglify: {
		production: {
			options: {
				mangle: false,
				sourceMap: true,
				sourceMapName: 'dist/js/app.map'
			},
			files: {
				'dist/global.js': ['src/js/*.js', '!src/js/vendors/*.js'] //['js/vendor/**/*.js', 'js/main.js']
			}
		}
	},

	imagemin: {
		production: {
			files: [{
				expand: true,
				cwd: 'src/images/',
				src: ['**/*.{png,jpg,gif}'],
				dest: 'dist/images/'
			}]
		}
	},

	svgmin: {
		production : {
			files: [{
				expand: true,
				cwd: 'src/images',
				src: '**/*.svg',
				dest: 'dist/images/'
			}]
		}
	},

	copy: {
		fonts: {
			expand: true,
			cwd: 'src/fonts',
			src: '**/*',
			dest: 'dist/fonts',
			flatten: true
		},
		jslibs: {
			expand: true,
			cwd: 'src/js/libs',
			src: '**/*',
			dest: 'dist/js/libs',
			//flatten: true
		},
		images: {			
			expand: true,
			cwd: 'src/images',
			src: '**/*',
			dest: 'dist/images',
			flatten: true
		}
	},

	// Compass and scss
	compass: {
		options: {
		//bundleExec: true,
		httpPath: './',
		cssDir: 'dist/css',
		sassDir: 'src/scss',
		imagesDir: 'src/images',
		javascriptsDir: 'src/js',
		fontsDir: 'src/fonts',
		assetCacheBuster: 'none',
		require: [
			//'sass-globbing',
			'susy'
			]
		},
		dev: {
			options: {
				environment: 'development',
				outputStyle: 'expanded',
				relativeAssets: true,
				raw: 'line_numbers = :true\n'
			}
		},
		dist: {
			options: {
				environment: 'production',
				outputStyle: 'compact',
				force: true
			}
		}
	},

	watch: {
		compass: {
			files: ['src/scss/*.{scss,sass}'],
			tasks: ['compass:dev']
		},
		scripts: {
			files: ['src/js/*.js', '!src/js/vendors/*.js'],
			tasks: ['concat'],
			options: {
				spawn: false,
			},
		},
		php: {
		  files: [ '*.php', '**/*.php' ],
		  options: {
		    reload: true
		  }
		},
		 
		copyfonts: {
			files: ['src/fonts/*'],
			tasks: ['copy:fonts'],
			options: {
				spawn: false,
			}
		},
		 
		copyjslibs: {
			files: ['src/js/libs/*'],
			tasks: ['copy:jslibs']
		},
		 
		copyimages: {
			files: ['src/images/*'],
			tasks: ['copy:images'],
			options: {
				spawn: false,
			}
		},

		options: {
			livereload: true,
		}
	},

	browserSync: {
		dev: {
			bsFiles: {
				src : [
				'dist/*.css',
				'*.php', 'templates/*.php'
				]
			},
			options: {
				watchTask: true,
				proxy: "local.dev/Site-CieLoba/",
				port:8888			}
		}
	}

});

// 3. Nous disons à Grunt que nous voulons utiliser ce plug-in.
grunt.loadNpmTasks('grunt-contrib-jshint');
grunt.loadNpmTasks('grunt-contrib-concat');
grunt.loadNpmTasks('grunt-contrib-uglify');
grunt.loadNpmTasks('grunt-contrib-compass');
grunt.loadNpmTasks('grunt-contrib-imagemin');
grunt.loadNpmTasks('grunt-svgmin');
grunt.loadNpmTasks('grunt-contrib-copy');
grunt.loadNpmTasks('grunt-contrib-watch');
grunt.loadNpmTasks('grunt-browser-sync');


// 4. Nous disons à Grunt quoi faire lorsque nous tapons "grunt" dans la console.
grunt.registerTask('default', ['watch']); // ['browserSync', 'watch']
grunt.registerTask('dev', ['watch']);
grunt.registerTask('dist', ['jshint', 'uglify', 'compass:dist', 'copy', 'imagemin:production', 'svgmin:production']);




};
