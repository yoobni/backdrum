CKEDITOR.plugins.add( 'chimageup', {
	icons: 'chimageup',
	init: function( editor ) {
		//Plugin logic goes here.
		editor.addCommand( 'imageUpload', {
			exec: function( editor ) {
				var now = new Date();
				editor.insertHtml( 'The current date and time is: <em>' + now.toString() + '</em>' );
			}
		});
		editor.ui.addButton( 'chimageup', {
			label: 'Image Upload',
			//command: 'imageUpload',
			toolbar: 'insert,0'
		});
		editor.on( 'instanceReady', function( ev ){
			var chupload = new plupload.Uploader({
				// General settings
				runtimes : 'html5,html4',
				browse_button : $('.cke_button__chimageup').attr('id'), // you can pass in id...
				url : CKEDITOR.basePath+"plugins/chimageup/uploader.php",
				chunk_size : '1mb',
				unique_names : true,

				// Resize images on client-side if we can
				//resize : { width : 320, height : 240, quality : 90 },

				filters : {
					max_file_size : '10mb',

					// Specify what files to browse for
					mime_types: [
						{title : "Image files", extensions : "jpg,gif,png"}
					]
				},
				multi_selection: true,
			         
				// PreInit events, bound before the internal events
				preinit : {
					Init: function(up, info) {
					},
			 
					UploadFile: function(up, file) {
						// You can override settings before the file is uploaded
						// up.setOption('url', 'upload.php?id=' + file.id);
						// up.setOption('multipart_params', {param1 : 'value1', param2 : 'value2'});
					}
				},
			 
				// Post init events, bound after the internal events
				init : {
					FilesAdded: function(up, files) {
						// Called when files are added to queue
			 
						plupload.each(files, function(file) {
						});

						up.start();
					},

					FileUploaded: function(up, file, info) {
						// Called when file has finished uploading
						//log('[FileUploaded] File:', file, "Info:", info);
						var path_exp = info.response.split('"');
						var path = path_exp[path_exp.length - 2];
						//alert(info.response);
						editor.insertHtml('<img src="http://'+window.location.host+'/uploaded/temp/'+path+'" />');
					},

					UploadComplete: function(up, files) {
						// Called when all files are either uploaded or failed
					},
			 
					Error: function(up, args) {
						// Called when error occurs
						alert("Error: " + err.code + ", Message: " + err.message + (err.file ? ", File: " + err.file.name : "") + "");
						up.refresh(); // Reposition Flash/Silverlight
			    	}
				}
			});
			chupload.init();
		});
	}
});