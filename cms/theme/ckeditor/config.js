/**
 * @license Copyright (c) 2003-2022, CKSource Holding sp. z o.o. All rights reserved.
 * For licensing, see https://ckeditor.com/legal/ckeditor-oss-license
 */

CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here. For example:
	config.removePlugins = 'easyimage';
	config.language = 'en';
	config.filebrowserBrowseUrl 		= '/ckfinder/ckfinder.php';
	config.filebrowserImageBrowseUrl 	= '/ckfinder/ckfinder.php?type=Images';
	config.filebrowserFlashBrowseUrl 	= '/ckfinder/ckfinder.php?type=Flash';
	config.filebrowserUploadUrl 		= '/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files';
	config.filebrowserImageUploadUrl 	= '/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images';
	config.filebrowserFlashUploadUrl 	= '/ckfinder/core/connector/php/connector.php';

	config.toolbarGroups = [
		{ name: 'document', groups: [ 'mode', 'document', 'doctools' ] },
		{ name: 'editing', groups: [ 'find', 'selection', 'spellchecker', 'editing' ] },
		{ name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align', 'bidi', 'paragraph' ] },
		{ name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
		{ name: 'clipboard', groups: [ 'clipboard', 'undo' ] },
		{ name: 'forms', groups: [ 'forms' ] },
		{ name: 'links', groups: [ 'links' ] },
		{ name: 'insert', groups: [ 'insert' ] },
		{ name: 'colors', groups: [ 'colors' ] },
		{ name: 'others', groups: [ 'others' ] },
		{ name: 'about', groups: [ 'about' ] },
		{ name: 'tools', groups: [ 'tools' ] },
		{ name: 'styles', groups: [ 'styles' ] },
	  ];


	config.removeButtons = 'Save,NewPage,Preview,Print,Templates,Find,Replace,SelectAll,Scayt,Form,Checkbox,Radio,TextField,Textarea,Select,Button,ImageButton,HiddenField,CreateDiv,Flash,PageBreak,Iframe,ShowBlocks,About';
	
	config.linkDefaultProtocol = 'https://';
};