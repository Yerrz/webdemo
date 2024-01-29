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
    <?php
    include "conn.php";
    $page = 1;
    $offset = 5;
    $sql = "SELECT * FROM roa_pdm_bookx limit " . ($page - 1) . "," . $offset;
    $query = mysqli_query($db, $sql);
    while ($info = mysqli_fetch_array($query)) {
      echo '<div class="div_book">
          <a href="bb.php">书名：' . $info['sBookName'] . '</a>
          <p>作者：' . $info["author"] . '</p>
          <p>简介：' . substr($info["sContentBrief"], 0, 500) . '</p>
          </div>';
    }
    ?>
  </div>
  <div class="weui-loadmore" style="border:1px solid red;">
    <i class="weui-loading"></i>
    <span class="weui-loadmore__tips">正在加载</span>
  </div>
  <script src="../js/jquery/jquery-1.8.0.js"></script>
  <script src="../js/jquery/fastclick.js"></script>
  <script>
    $(function() {
      FastClick.attach(document.body);
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

    window.addEventListener('scroll', () => {
      const position = getScrollPosition(); // 假设这个函数返回的是一个对象，包含滚动条的位置信息
      const scrollTop = position.scrollTop;
      console.log('Vertical Scroll Top:', scrollTop);

      // 将滚动位置附加到URL后
      const newUrl = window.location.origin + window.location.pathname + '?scrollTop=' + scrollTop;

      // 使用pushState方法更新浏览器的历史记录和地址栏
      window.history.pushState({
        scrollTop
      }, '', newUrl);
    });

    // 如果页面加载时已经有scrollTop查询参数，则读取并设置初始滚动位置（可选）
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.has('scrollTop')) {
      const scrollTop = parseInt(urlParams.get('scrollTop'));
      window.scrollTo(0, scrollTop);
    }
  </script>
  <script src="../js/jquery/jquery-weui.js"></script>

  <script type="text/javascript">
    $(function() {
      var loading = false;
      var count = 1;
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
            const newUrl = window.location.origin + window.location.pathname + '?page=' + count +'&scrollTop=' + window.history.state.scrollTop;

            // 使用pushState方法更新浏览器的历史记录和地址栏
            window.history.pushState(null,'',newUrl);
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
            // if(jsondata.msg == "S"){
            //   data = jsondata.data;
            //   loading = true;
            //   console.log("触发infinite");
            //   $("#list").append(data);
            // }
            // else{
            //   console.log("error");
            // }
          }
        })
      })
      // var loading = false;
      // var count = 1;
      // $(document.body).infinite().on("infinite", function() {
      //   if (loading) return;
      //   count += 1;
      //   console.log("当前页数为：",count);
      //   $.ajax({
      //     url: "ajax.php",
      //     type: "get",
      //     data: {
      //       page: count
      //     },
      //     dataType: "json",
      //     success: function(data) {
      //       $jsondata = JSON.parse(data);
      //       if($jsondata.msg == "S"){
      //         $data = $jsondata.data;
      //         loading = true;
      //         console.log("触发infinite");
      //         $("#list").append($data);
      //         // setTimeout(function() {
      //         //   // $("#list").append("<div class='div_book'><a href='bb.php'>标题</a><p>《世界著名计算机教材精选·人工智能:一种现代的方法(第3版)》英文版有1100多页，教学内容非常丰富，不但涵盖了人工智能基础、问题求解、知识推理与规划等经典内容，而且还包括不确定知识与推理、机器学习、通讯感知与行动等专门知识的介绍。目前我们为本科生开设的学科基础必修课“人工智能导论”主要介绍其中的经典内容，而研究生必修的核心课程“人工智能原理”主要关注其中的专门知识。其实《世界著名计算机教材精选·人工智能:一种现代的方法(第3版)》也适合希望提高自身计算系统设计水平的广大应用计算技术的社会公众，对参加信息学奥林匹克竞赛和ACM程序设计竞赛的选手及其教练员也有一定的参考作用。</p></div>");
      //         //   $("#list").append($data);
      //         //   loading = false;
      //         // }, 2000);
      //       }
      //       else{
      //         console.log("error");
      //       }
      //     }
      //   });



      // loading = true;
      // console.log("触发infinite");
      // setTimeout(function() {
      //   // $("#list").append("<div class='div_book'><a href='bb.php'>标题</a><p>《世界著名计算机教材精选·人工智能:一种现代的方法(第3版)》英文版有1100多页，教学内容非常丰富，不但涵盖了人工智能基础、问题求解、知识推理与规划等经典内容，而且还包括不确定知识与推理、机器学习、通讯感知与行动等专门知识的介绍。目前我们为本科生开设的学科基础必修课“人工智能导论”主要介绍其中的经典内容，而研究生必修的核心课程“人工智能原理”主要关注其中的专门知识。其实《世界著名计算机教材精选·人工智能:一种现代的方法(第3版)》也适合希望提高自身计算系统设计水平的广大应用计算技术的社会公众，对参加信息学奥林匹克竞赛和ACM程序设计竞赛的选手及其教练员也有一定的参考作用。</p></div>");
      //   $("#list").append($data);
      //   loading = false;
      // }, 2000);
    });
  </script>
</body>

</html>