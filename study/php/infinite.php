<!DOCTYPE html>
<html>

<head>
  <title>滚动加载</title>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">

  <meta name="description" content="Write an awesome description for your new site here. You can edit this line in _config.yml. It will appear in your document head meta (for Google search results) and in your feed.xml site description.
">

  <link rel="stylesheet" href="../css/weui.min.css">
  <link rel="stylesheet" href="../css/jquery-weui.css">
  <link rel="stylesheet" href="../css/demos.css">
  <style>
    .div_book {
      border: 1px solid #ccc;
      padding: 10px;
      margin: 5px;
    }
  </style>

</head>

<body ontouchstart>

  <h1 class="demos-title">滚动加载</h1>
  <div id="list" class='demos-content-padded'>
    
  </div>
  <div class="weui-loadmore" style="border:1px solid red;">
    <i class="weui-loading"></i>
    <span class="weui-loadmore__tips">点击加载</span>
  </div>
  <script src="../js/jquery/jquery-1.8.0.js"></script>
  <script src="../js/jquery/fastclick.js"></script>
  <script>
    $(function() {
      FastClick.attach(document.body);
    });
  </script>
  <script src="../js/jquery/jquery-weui.js"></script>

  <script type="text/javascript">
    $(function() {
      var loading = false;
      var count = 1;
      $('.weui-loadmore').hide();
      $.ajax({
        url: "ajax.php",
        type: "get",
        data: {
          page: count
        },
        dataType: "json",
        success: function(data) {
          // 将滚动位置附加到URL后
          const newUrl = window.location.origin + window.location.pathname + '?page=' + count;
          // 使用pushState方法更新浏览器的历史记录和地址栏
          window.history.pushState(null, '', newUrl);
          var jsondata = $.parseJSON(data);
          if (data.msg == "S") {
            res = data.data;
            $("#list").append(res);
            $('.weui-loadmore').show();
          } else {
            console.log("error");
          }
        }
      });
      // 点击加载
      $(".weui-loadmore").click(function() {
        console.log("点击了");
        count += 1;
        console.log("当前页数为：", count);
        $.ajax({
          url: "ajax.php",
          type: "get",
          data: {
            page: count
          },
          dataType: "json",
          success: function(data) {
            // 将滚动位置附加到URL后
            const newUrl = window.location.origin + window.location.pathname + '?page=' + count + '&scrollTop=' + window.history.state.scrollTop;

            // 使用pushState方法更新浏览器的历史记录和地址栏
            window.history.pushState(null, '', newUrl);
            console.log("aa");
            console.log("成功获取数据", data);
            var jsondata = $.parseJSON(data);
            console.log(data.msg);
            if (data.msg == "S") {
              res = data.data;
              loading = true;
              console.log("触发infinite");
              $("#list").append(res);
            } else {
              console.log("error");
            }
          }
        })
      });
      // 自动加载完
    });

    // 获取滚动条位置
    function getScrollPosition() {
      let scrollTop = 0;

      // 现代浏览器及大部分主流浏览器
      if (typeof window.pageYOffset !== 'undefined') {
        scrollTop = window.pageYOffset;
      } else {
        // IE等老版本浏览器
        const doc = document.documentElement || document.body.parentNode || document.body;
        scrollTop = doc.scrollTop;
      }

      return {
        scrollTop,
      };
    }

    // 获取链接参数-页码
    function getUrlParams() {
      var searchParams = new URLSearchParams(window.location.search);
      var paramsObj = {};

      for (var param of searchParams.entries()) {
        paramsObj[param[0]] = param[1];
      }

      return paramsObj;
    }

    window.addEventListener('scroll', () => {
      const position = getScrollPosition(); // 假设这个函数返回的是一个对象，包含滚动条的位置信息
      const scrollTop = position.scrollTop;
      console.log('Vertical Scroll Top:', scrollTop);

      const params = getUrlParams();
      const page = params.page;
      console.log('page:', page);

      // 将滚动位置附加到URL后
      const newUrl = window.location.origin + window.location.pathname + '?page=' + page + '&scrollTop=' + scrollTop;

      // 使用pushState方法更新浏览器的历史记录和地址栏
      window.history.pushState({
        scrollTop,page
      }, '', newUrl);
    });

    // 如果页面加载时已经有scrollTop查询参数，则读取并设置初始滚动位置（可选）
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.has('scrollTop')) {
      const scrollTop = parseInt(urlParams.get('scrollTop'));
      window.scrollTo(0, scrollTop);
    }
  </script>
</body>

</html>