/**
 * @license Copyright (c) 2003-2013, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.html or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here.
	// For complete reference see:
	// http://docs.ckeditor.com/#!/api/CKEDITOR.config
    
    
	config.language = "ko";
	config.skin = "office2013";
	config.extraPlugins = 'chimageup,youtube,font,tableresize,tabletools,dialogadvtab,preview,justify';
	config.extraAllowedContent =  'img[!src,alt,width,height]{float};' + 'h1 h2 div';
	config.height = 500;
	//config.toolbar_name = [[ 'doksoft_instant_image' ]];
	// The toolbar groups arrangement, optimized for two toolbar rows.
	/*
	config.toolbarGroups = [		
		{ name: 'editing',     groups: [ 'find', 'selection', 'spellchecker' ] },
		{ name: 'links' },
		{ name: 'insert' },
		{ name: 'forms' },
		{ name: 'tools' }, 
		{ name: 'document',	   groups: [ 'mode', 'document', 'doctools' ] },
		{ name: 'others' },
		'/',
		{ name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
		{ name: 'paragraph',   groups: [ 'list', 'indent', 'blocks', 'align', 'bidi' ] },
		{ name: 'styles' },
		{ name: 'colors' }
	];
*/
	config.font_defaultLabel = "나눔고딕";
    config.font_names = "굴림;돋움;바탕;궁서;굴림체;돋움체;바탕체;궁서체;나눔고딕;나눔명조;"+
        'Arial;Comic Sans MS;Courier New;Lucida Sans Unicode;monospace;sans-serif;serif;Tahoma;Times New Roman;Verdana';
    config.fontSize_defaultLabel = "14pt";
    config.fontSize_sizes = "7pt/9px;8pt/11px;9pt/12px;10pt/13px;11pt/15px;14pt/19px;18pt/24px;24pt/32px;36/48px;";
    
	config.toolbar =  [
		[
			'Font','FontSize', '-',
			'Bold','Italic','Underline','Strike', '-',
			'Subscript','Superscript', '-',
			'TextColor','BGColor', '-',
			'JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock', '-',
			'Link','Unlink', '-',
			'Find','Replace','SelectAll','RemoveFormat'
		], '-',
		[
			'Preview','Source','-','ShowBlocks','-','Undo','Redo', '-',
			'chimageup','youtube','Table','SpecialChar'
		]
	];
	
	// Remove some buttons provided by the standard plugins, which are
	// not needed in the Standard(s) toolbar.
	//config.removeButtons = 'Image';

	// Set the most common block elements.
	config.format_tags = 'p;h1;h2;h3;pre';

	// Simplify the dialog windows.
	config.removeDialogTabs = 'image:advanced;link:advanced';
};

/*CKEDITOR.plugins.add( 'img',
{
    init: function( editor )
    {
        editor.addCommand( 'insertImg',
            {
                exec : function( editor )
                {    
                    var imgurl=prompt("Insert url image");
                    editor.insertHtml('<img src="'+imgurl+'" />');
                }
            });
        editor.ui.addButton('img',
        {
            label: 'Insert img',
            command: 'insertImg'
        } );
    }
} );*/