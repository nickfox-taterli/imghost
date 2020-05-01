

<!DOCTYPE html>
<html lang="zh-cn">
<head>
    <meta charset="utf-8">
    <title>简单图床</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

        <link href="https://cdn.bootcss.com/zui/1.8.1/css/zui.min.css" rel="stylesheet">
        <link href="https://cdn.bootcss.com/zui/1.8.1/lib/uploader/zui.uploader.min.css" rel="stylesheet">

        <script src="https://cdn.bootcss.com/jquery/3.3.1/jquery.min.js?v3.3.1"></script>
        <script src="https://cdn.bootcss.com/zui/1.9.1/js/zui.min.js?v1.9.1"></script>
        <script src="https://cdn.bootcss.com/zui/1.9.1/lib/uploader/zui.uploader.min.js?v1.9.1"></script>
        <script src="https://cdn.jsdelivr.net/gh/icret/easyImages@1.5.3/static/qrcode.min.js?v1"></script>
        <script src="https://cdn.bootcss.com/clipboard.js/2.0.4/clipboard.min.js?v2.0.4"></script>

    <style>
        .uploader-files{
            min-height:160px;
            border-style:dashed;
        }
    </style>
</head>
<body class="container">
<!-- 顶部导航栏END -->
    <div class="container">
  <div class="col-md-12">
       <div id='uploaderExample' class="uploader col-md-10 col-md-offset-1" data-ride="uploader" data-url="store">
        <div class="uploader-message text-center">
          <div class="content"></div>
          <button type="button" class="close">×</button>
        </div>
        <div class="uploader-files file-list file-list-lg" data-drag-placeholder="Ctrl+V粘贴/选择文件/将图片直接拖拽至此处 (Limit:5MB)"></div>
        <div class="uploader-actions">
          <div class="uploader-status pull-right text-muted"></div>
          <button type="button" class="btn btn-link uploader-btn-browse">
            <i class="icon icon-plus"></i>选择文件</button>
          <button type="button" class="btn btn-link uploader-btn-start">
            <i class="icon icon-cloud-upload"></i>开始上传</button>
            <button type="button" class="btn btn-link uploader-btn-stop">
            <i class="icon icon-pause"></i>暂停上传</button>
        </div>
      </div>
      <div class="col-md-8 col-md-offset-2">
        <ul class="nav nav-tabs">
          <li class="active">
            <a href="#" data-target="#tab2Content1" data-toggle="tab">直链</a></li>
          <li>
            <a href="#" data-target="#tab2Content2" data-toggle="tab">论坛代码</a></li>
          <li>
            <a href="#" data-target="#tab2Content3" data-toggle="tab">MarkDown</a></li>
          <li>
            <a href="#" data-target="#tab2Content4" data-toggle="tab">HTML</a></li>
        </ul>
        <div class="tab-content" align="right">
          <div class="tab-pane fade active in" id="tab2Content1">
            <textarea class="form-control" style="text-align: center;min-height: 100px;" id="links" readonly></textarea>
            <button id="btnLinks" class="btn copyBtn1" data-loading-text="已经复制链接..." style="margin-top:10px;"><i
                class="icon icon-copy"></i> 复制</button>
          </div>
          <div class="tab-pane fade" id="tab2Content2">
            <textarea class="form-control" style="text-align: center;min-height: 100px;" id="bbscode"
              readonly></textarea>
            <button id="btnBbscode" class="btn copyBtn2" data-loading-text="已经复制链接..." style="margin-top:10px;"><i
                class="icon icon-copy"></i> 复制</button>
          </div>
          <div class="tab-pane fade" id="tab2Content3">
            <textarea class="form-control" style="text-align: center;min-height: 100px;" id="markdown"
              readonly></textarea>
            <button id="btnMarkDown" class="btn copyBtn3" data-loading-text="已经复制链接..." style="margin-top:10px;"><i
                class="icon icon-copy"></i> 复制</button>
          </div>
          <div class="tab-pane fade" id="tab2Content4">
            <textarea class="form-control" style="text-align: center;min-height: 100px;" id="html" readonly></textarea>
            <button id="btnHtml" class="btn copyBtn4" data-loading-text="已经复制链接..." style="margin-top:10px;"><i
                class="icon icon-copy"></i> 复制</button>
          </div>
        </div>
      </div>
  </div>
</div>
    <script src="static/paste.js"></script>
    <script src="static/copy_btn.js"></script>
    <script>
        $('#uploaderExample').uploader({
            // 当选择文件后立即自动进行上传操作
            autoUpload: false,
            // 文件上传提交地址
            url: 'store',
            // 最大支持的上传文件
            max_file_size: 5242880 ,
            // 是否分片上传 0为不分片 经测试分片容易使图片上传失败
            chunk_size: 0,
            //点击文件列表上传文件
            browseByClickList: true,
            // 上传格式过滤
            filters: { // 只允许上传图片或图标（.ico）
                mime_types: [{
                    title: '图片',
                    extensions: 'bmp,jpg,png,tif,gif,pcx,tga,svg,webp,jpeg,tga,svg,ico'
                }, {
                    title: '图标',
                    extensions: 'ico'
                }],
                prevent_duplicates: true
            },
            // 限制文件上传数目
            limitFilesCount: 30 ,
            // 自动上传失败的文件
            autoResetFails: true,

            responseHandler: function (responseObject, file) {
                // 当服务器返回的文本内容包含 `'success'` 文件上传成功
                if (responseObject.response.indexOf('success')) {
                    console.log(responseObject.response);
                    var obj = JSON.parse(responseObject.response); //由JSON字符串转换为JSON对象
                    var links = document.getElementById("links");
                    links.innerHTML += obj.url + "\n";

                    var bbscode = document.getElementById("bbscode");
                    bbscode.innerHTML += "[img]" + obj.url + "[/img]\n";

                    var markdown = document.getElementById("markdown");
                    markdown.innerHTML += "![](" + obj.url + ")\n";

                    var html = document.getElementById("html");
                    html.innerHTML += "&lt;img src=\"" + obj.url + "\" /&#62;\n";
                } else {
                    return '上传失败。服务器返回了一个错误：' + responseObject.response;
                }
            }
        });
    </script>
  <footer class="text-muted small col-md-12" style="text-align: center">
  <hr />
  Author:<a href="https://www.taterli.com/" target="_blank"> TaterLi</a>
  </footer>
</body>
</html>
