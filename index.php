<?php
// ##########################################################################
// 環境設定

$Env = array(
    'PageTitle' => "Upload site",     // ページタイトルを設定
    'MaxFileSize' => 1024000000,     // 最大ファイルサイズ(バイト表示)
    'FileTypeList' => array('gif', 'jpg', 'jpeg','xls', 'xlsx', 'doc', 'docx', 'ppt', 'pptx', 'zip', 'pdf', 'htm', 'html', 'css')    // アップロード許可ファイル拡張子
);


// ##########################################################################
// コード

if (isset($_POST['mode'])){
    $mode = h($_POST['mode']);
} else {
    $mode = '';
}

// 初期画面
if ( $mode === '') {
    startview($Env);
}

// ファイルアップ受付処理
if ($mode === "regist") {
    print_r($_POST);
    print_r($_FILES);
    $msg = fileUpload($_POST, $_FILES, $Env);
    echo $msg[1];
}

function startview($Env)
{
    $Pager = ' <a href="./upload.cgi?pg=20">2</a> |';
    $FileList = "";
    include("./template/toppage.php");
}


///////////////////////////////////////////////////////////////////////////
// Functions
///////////////////////////////////////////////////////////////////////////


//
// upload file type check and save files
//
function fileUpload ($post, $files, $Env ){
    // from http://qiita.com/mpyw/items/73ee77a9535cc65eff1e
    global $CFG;
    if (isset($files['upfile']['error']) && is_int($files['upfile']['error'])) {
        try {

	    // $files['upfile']['error'] の値を確認
	    switch ($files['upfile']['error']) {
	        case UPLOAD_ERR_OK: // OK
	            break;
	        case UPLOAD_ERR_NO_FILE:   // ファイル未選択
	            throw new RuntimeException('ファイルが選択されていません');
	        case UPLOAD_ERR_INI_SIZE:  // php.ini定義の最大サイズ超過
	        case UPLOAD_ERR_FORM_SIZE: // フォーム定義の最大サイズ超過
	            throw new RuntimeException('ファイルサイズが大きすぎます');
	        default:
	            throw new RuntimeException('その他のエラーが発生しました');
	    }

	    // $files['upfile']['mime']の値はブラウザ側で偽装可能なので、MIMEタイプを自前でチェックする
	    $mimetype = mime_content_type($files['upfile']['tmp_name']);

            $Ext = mimeChk($mimetype);

            if (! isExtChk( $Ext, $Env )) {
	        throw new RuntimeException('許可されていないファイル形式です');
            }


	    // ファイルデータからSHA-1ハッシュを取ってファイル名を決定し、ファイルを保存する
	    $filename = sha1_file($files['upfile']['tmp_name']) . '.' . $Ext;
	    $path = sprintf( __DIR__.'/files/%s', $filename);
	    if (! move_uploaded_file($files['upfile']['tmp_name'], $path)) {
	        throw new RuntimeException('ファイル保存時にエラーが発生しました');
	    }
	    chmod($path, 0644);
	    $msg = array('green', 'ファイルは正常にアップロードされました', $filename);

            // ファイル情報の書き込み


        } catch (RuntimeException $e) {
	    $msg = array('red', $e->getMessage(), '' );
        }
        return $msg;
    }
}

//
// is allowd file extension
//
function isExtChk( $Ext, $Env )
{
    foreach ( $Env['FileTypeList'] as $type ){
        if ( $type === $Ext ){
            return 1;
        }
    }
    return 0;
}


//
// ファイルの mime type から拡張子を返す
//
function mimeChk($type)
{
    $mimeType = mimeTypes();
    foreach ( $mimeType as $ext => $mime ) {
        if ($type === $mime){
            return $ext;
        }
    }
    return 0;
}

//
// For display FileTypeList
//
function FileTypeList($Env)
{
    $typelist = '';
    foreach ( $Env['FileTypeList'] as $type ){
        $typelist .= $type . ', ';
    }
    return $typelist;
}

//
// Only return Mime Type list
//
function mimeTypes()
{
    $MimeTypes = array(
        '7z' => 'application/x-7z-compressed',
        'css' => 'text/css',
        'doc' => 'application/msword',
        'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'dotx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.template',
        'htm' => 'text/html',
        'html' => 'text/html',
        'jpeg' => 'image/jpeg',
        'jpg' => 'image/jpeg',
        'js' => 'application/javascript',
        'json' => 'application/json',
        'lzh' => 'application/x-lzh-compressed',
        'mp3' => 'audio/mpeg',
        'mp4' => 'video/mp4',
        'mpeg' => 'video/mpeg',
        'mpg' => 'video/mpeg',
        'mpg4' => 'video/mp4',
        'pdf' => 'application/pdf',
        'png' => 'image/png',
        'ppt' => 'application/vnd.ms-powerpoint',
        'pptx' => 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
        'svg' => 'image/svg+xml',
        'tar' => 'application/x-tar',
        'txt' => 'text/plain',
        'weba' => 'audio/webm',
        'webm' => 'video/webm',
        'webp' => 'image/webp',
        'xls' => 'application/vnd.ms-excel',
        'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'xml' => 'application/xml',
        'zip' => 'application/zip'
    );

    return $MimeTypes;
}


//
// htmlspecialchars
//
function h ($str)
{
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}
