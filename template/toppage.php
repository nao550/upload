<!doctype html>
<head>
  <title><?= $PageTitle ?></title>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <meta name="viewport" content="user-scalable=yes, width=device-width, initial-scale=1.0, maximum-scale=1"/>
  <meta name="description" content="" />
  <meta name="keywords" content="" />
  <link rel='stylesheet' href="./css/style.css">
</head>
<body>
  <h1 name='top' id="top"><?= $Env['PageTitle'] ?></h1>
  <p>・ 講師の許可なくファイルのアップロード・ダウンロードを行わないでください。</p>
  <form action="./index.php" method="post" enctype="multipart/form-data">
    <input type="hidden" name="mode" value="regist">
    <table class="upform">
      <tr>
        <td>ファイル</td>
        <td>
          <input type="file" name="upfile" size="30">
          &nbsp;（最大容量: <?= $Env['MaxFileSize'] / 1024 ?>KB）
        </td>
      </tr><tr>
        <td>コメント</td>
        <td>
          <input type="text" name="comment" size="40" value="コメント入力" maxlength="15">
          <input type="submit" value="送信する">
        </td>
      </tr><tr>
        <td>削除キー</td>
        <td>
          <input type="password" name="delkey" size="8" maxlength="8">
          &nbsp;&nbsp;
          暗証キー
          <input type="password" name="pwdkey" size="8" maxlength="8"/>
        </td>

      </tr>
    </table>
  </form>

  <p>Page: | <b>1</b> | <?= $Pager ?></p>

  <table class="list">
    <tr>
      <th>ファイル [容量]</th>
      <th>コメント</th>
      <th>DL数</th>
      <th>投稿日</th>
      <th>MIMEタイプ</th>
      <th>削除</th>
    </tr>
    <?= $FileList ?>
  </table>

  <p>Page: | <b>1</b> | <?= $Pager ?></p>

  <p></p>
  <hr class="rule">
  <b name="rule" id="rule" class="rule">■利用規約</b>
  <ul>
    <li>授業に関係のないファイル、不必要なファイルはアップロードしないでください。
      <li>管理者が、上記の要件に違反するものと判断したファイルは予告なく削除します。
        <li>記録する記事データは最大<b>300</b>件までです。これを超えると古い順に自動削除されます。
  </ul>
  <div class="pgtop"><a href="#top" title="ページトップへ">pagetop</a></div>
  <hr class="rule">
  <b class="rule">■使い方</b>
  <ul>
    <li>アップロード可能なファイルサイズは、最大<b><?= $Env['MaxFileSize']/1024 ?>KB</b>までです。
      <li>「削除キー」にパスワードを入力しておくと、後程ご自分で提出ファイルを削除することができます。
        <!-- <li>「暗証キー」にパスワードを入力しておくと、ダウンロードする場合にその暗証キーが必要となります。
             <li>ファイル先頭のアイコン <img src="./icon/lock.gif" alt="ロック" class="icon"> は、ダウンロード時に暗証キーが必要です。-->
        <li>使用可能な拡張子は次のとおりです。<br><span class="ext"><?= FileTypeList($Env) ?></span>
  </ul>
  <div class="pgtop"><a href="#top" title="ページトップへ">pagetop</a></div>
  <p style="margin-top:2em;text-align:center;font-family:Verdana,Helvetica,Arial;font-size:10px;">
    - <a href="https://github.com/nao550/upload" target="_top">UP LOADER</a> -
  </p>
</body>
</html>
