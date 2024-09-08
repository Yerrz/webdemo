<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>my self canledar</title>
    <!-- <script src="//code.jquery.com/jquery-1.10.2.js"></script> -->
    <!-- <script src="../jquery/jquery-1.8.0.js"></script> -->
    <style>
        body {
    margin: 40px 10px;
    padding: 0;
    font-family: Arial, Helvetica Neue, Helvetica, sans-serif;
    font-size: 14px;
  }
      #calendar {
        max-width: 1100px;
        height: 800px;
        margin: 0 auto;
      }
      .fc {
  position: relative;
  z-index: 1;
    display: flex;
    flex-grow: 1;
    flex-direction: column;
}
      .fc .fc-button {
    /* background-color: transparent; */
    border: 1px solid transparent;
    border-radius: 0.25em;
    display: inline-block;
    font-size: 1em;
    font-weight: 400;
    line-height: 1.5;
    padding: 0.4em 0.65em;
    text-align: center;
    user-select: none;
    vertical-align: middle;
}
.fc .fc-button .fc-icon {
    /* font-size: 1.5em; */
    vertical-align: middle;
}
.fc .fc-icon {
    speak: none;
    -webkit-font-smoothing: antialiased;
    display: inline-block;
    font-style: normal;
    font-variant: normal;
    font-weight: 400;
    height: 1em;
    line-height: 1;
    text-align: center;
    text-transform: none;
    user-select: none;
    width: 1em;
    font-family: fcicons !important;
}
.fc .fc-button-primary{
  background-color: #2c3e50;
    border-color: #2c3e50;
    color: #fff;
}

.fc .fc-header-toolbar{
  margin-bottom: 1.5em;
  align-items: center;
    display: flex;
    justify-content: space-between;
}
.fc-header-toolbar .fc-header-toolbar-title{
  padding: 0 2px;
}
.fc-header-toolbar .fc-button-group {
    /* display: inline-flex; */
    display: flex;
    align-items: center;
    position: relative;
    vertical-align: middle;
    /* flex: 1 1 auto; */
}
.fc-header-toolbar .fc-button-group > .fc-button:not(:first-child) {
    border-bottom-left-radius: 0px;
    border-top-left-radius: 0px;
}
.fc-header-toolbar .fc-button-group > .fc-button:not(:last-child) {
    border-bottom-right-radius: 0px;
    border-top-right-radius: 0px;
}

.fc-header-toolbar .fc-button:not(:disabled) {
    cursor: pointer;
}
.fc-header-toolbar .fc-icon-premonth::before {
  content: "<";
}
.fc-header-toolbar .fc-icon-nextmonth::before {
  content: ">";
}

.fc table {
  width: 1098px;
  height: 717px;
  /* border-left-style: hidden; */
    /* border-right-style: hidden; */
    /* border-top-style: hidden; */
    border-collapse: collapse;
    border-spacing: 0px;
}
.fc-table td, .fc-table th {
    border: 1px solid #ddd;
}

.fc .fc-day{min-height: 100%;position: relative;
  /* background-color:rgba(255,220,40,.15); */
  padding: 0px;
  vertical-align: top;}
.fc .fc-day-top{display: flex;align-items: center;
  flex-direction: row;justify-content: space-between;padding: 0 5px;
}
.fc .fc-day-number, .fc .fc-day-festival {
  display: inline-block;
    padding: 4px;
    text-align: center;
    /* position: relative; */
}
.fc .fc-day-festival{color: #ddd;}
.fc-day .fc-day-events{    
  left: 0px;
  position: absolute;
  right: 0px;
  margin-top: 1px;
  /* background-color: #3788d8; */
  border-radius: 3px;
  white-space: nowrap;
  /* border: 1px solid #3788d8; */
  /* color: #fff; */
  overflow: hidden;
  /* font-size: .85em; */
}
.fc-day-events .fc-event{
  position: relative;
  padding: 2px 0px;
  margin-top: 1px;
  margin: 0 2px;
  border-radius: 3px;
}
.fc-day-events .fc-day-events-main{
  color: #fff;
  background-color: #3788d8;
  border: 1px solid #3788d8;
}
.fc-day-events .fc-day-events-calendar{
  display: flex;
  align-items: center;
}
.fc-day-events .fc-day-events-calendar:hover{
  background-color: #f5f5f5;
}
.fc-day-events-calendar .fc-day-events-calendar-dot{
  border: 4px solid #3788d8;
    border-radius: 4px;
    box-sizing: content-box;
    height: 0px;
    margin: 0px 4px;
    width: 0px;
}
.fc-day-events-calendar .fc-day-events-calendar-time{
margin-right: 3px;
}
.fc-day-events-calendar .fc-day-events-calendar-title {
    flex-grow: 1;
    flex-shrink: 1;
    font-weight: 700;
    min-width: 0px;
    overflow: hidden;
}
    </style>
</head>
<body>
    <div id="calendar" class="fc">
    <div class="fc-header-toolbar">
                <div class="fc-toolbar-chunk fc-toolbar-left"><button type="button" class="fc-button-today fc-button fc-button-primary">今天</button></div>
                <div class="fc-toolbar-chunk fc-toolbar-center fc-button-group">
                  <button type="button" class="fc-button-premonth fc-button fc-button-primary"><span class="fc-icon fc-icon-premonth"></span></button>'
                  <div><h2 class="fc-header-toolbar-title"><span class="fc-header-title-year"></span> 年 <span class="fc-header-title-month"></span>月</h2></div>
                  <button type="button" class="fc-button-nextmonth fc-button fc-button-primary"><span class="fc-icon fc-icon-nextmonth"></span></button>
                </div>
                <div class="fc-toolbar-chunk fc-toolbar-right fc-button-group">
                  <button type="button" class="fc-button-month fc-button fc-button-primary">月</button>
                  <button type="button" class="fc-button-eventsList fc-button fc-button-primary">事件</button>
                </div>
                </div>

      <div class="fc-dayGridMonth-view" id="dayGridMonth"></div>
    </div>
</body>
</html>

<script src="../jquery/jquery-1.8.0.js"></script>
<script type="text/javascript">
  $(function(){
    console.log("calendar");
    // 获取当前日期
    var currentDate = new Date();
    // var currentYear = currentDate.getFullYear();
    // var currentMonth = currentDate.getMonth();
    // var currentDay = currentDate.getDate();

    // 将日历显示在页面中
    // document.getElementById('calendar').innerHTML = buildCalendar(currentYear, currentMonth);

    // 将日历显示在页面中
    // var HtmlData = buildCalendar(currentDate.getFullYear(), currentDate.getMonth());
    var HtmlData = buildCalendar(2024, 2);
    $('#dayGridMonth').html(HtmlData);
    $('.fc-header-title-year').text(2024);
    $('.fc-header-title-month').text(3);


    $('.fc-button-today').click(function(){
      var HtmlData = buildCalendar(currentDate.getFullYear(), currentDate.getMonth());
      $('#dayGridMonth').html(HtmlData);
    });
    $('.fc-button-premonth').click(function(){
      var titleYear = parseInt($('.fc-header-title-year').text());
      var titleMonth = parseInt($('.fc-header-title-month').text());
      console.log("切换到下月"+titleMonth);

      var HtmlData = buildCalendar(titleYear, (titleMonth-2));
      $('#dayGridMonth').html(HtmlData);
    });
    $('.fc-button-nextmonth').click(function(){
      // 获取面板标题
      var titleYear = parseInt($('.fc-header-title-year').text());
      var titleMonth = parseInt($('.fc-header-title-month').text());
      console.log(titleYear+'-'+titleMonth);
      console.log("切换到下月"+titleMonth);

      $('.fc-header-title-year').text(titleYear);
      $('.fc-header-title-month').text(titleMonth+1);
      var HtmlData = buildCalendar(titleYear, titleMonth);
      $('#dayGridMonth').html(HtmlData);
    });
  });
 
// 构建日历的HTML结构
function buildCalendar(year, month) {
  year = parseInt(year);
  month = parseInt(month);
  console.log('buildCalendar:'+year+'-'+month);
  // $('.fc-header-title-year').text(year);
  // $('.fc-header-title-month').text(month);

  // 获取当前日期
  var currentDate = new Date();
  var currentYear = currentDate.getFullYear();
  var currentMonth = currentDate.getMonth();
  var currentDay = currentDate.getDate();

  // var firstDay = new Date(year, month, 1).getDay(); // 获取每月第一天是星期几
  var firstDay = new Date(year, month, 1).getDay(); // 获取每月第一天是星期几(按日历周七、周一到周六)
  // console.log(firstDay);
  var daysInMonth = new Date(year, month + 1, 0).getDate(); // 获取每月总天数
  // console.log(daysInMonth);

  // 获取每月第一天是星期几(按日历周一到周七)
  if(firstDay == 0) firstDay = 6;
  else firstDay -= 1;
 
  var calendarHTML = '';

  // 工具栏
  // var month2 = month + 1;
  // calendarHTML += '<div class="fc-header-toolbar">'
  //               +'<div class="fc-toolbar-chunk fc-toolbar-left"><button type="button" class="fc-button-today fc-button fc-button-primary">今天</button></div>'
  //               +'<div class="fc-toolbar-chunk fc-toolbar-center fc-button-group">'
  //                 +'<button type="button" class="fc-button-premonth fc-button fc-button-primary"><span class="fc-icon fc-icon-premonth"></span></button>'
  //                 +'<div><h2 class="fc-header-toolbar-title"><span class="fc-header-title-year">'+ year +'</span> 年 <span class="fc-header-title-month">' + (month2) + '</span>月</h2></div>'
  //                 +'<button type="button" class="fc-button-nextmonth fc-button fc-button-primary"><span class="fc-icon fc-icon-nextmonth"></span></button>'
  //               +'</div>'
  //               +'<div class="fc-toolbar-chunk fc-toolbar-right fc-button-group">'
  //                 +'<button type="button" class="fc-button-month fc-button fc-button-primary">月</button>'
  //                 +'<button type="button" class="fc-button-eventsList fc-button fc-button-primary">事件</button>'
  //               +'</div>'
  //               +'</div>';

  // 日历表格
  calendarHTML += '<table class="fc-table">';
 
  // 构建表头，显示周几
  // calendarHTML += '<tr class="row"><th><a>日</a></th><th><a>一</a></th><th><a>二</a></th><th><a>三</a></th><th><a>四</a></th><th><a>五</a></th><th><a>六</a></th></tr></thead>';
  calendarHTML += '<thead><tr class="row"><th>周一</th><th>周二</th><th>周三</th><th>周四</th><th>周五</th><th>周六</th><th>周日</th></tr></thead>';
 
  // 构建日历主体
  calendarHTML += '<tbody><tr>';
 
  // 补充空白天数
  for (var i = 0; i < firstDay; i++) {
    calendarHTML += '<td></td>';
  }
 
  for (var day = 1; day <= daysInMonth; day++) {
    // 换行
    if ((day + firstDay - 1) % 7 === 0 && day !== 1) {
      calendarHTML += '</tr><tr>';
    }
 
    // 高亮当前日期
    if (year === currentYear && month === currentMonth && day === currentDay) {
      calendarHTML += '<td class="fc-day fc-day-today"><div class="fc-day-top"><a class="fc-day-number">' + day + '</a><a class="fc-day-festival"></a></div>';
    } else {
      calendarHTML += '<td class="fc-day fc-day-others"><div class="fc-day-top"><a class="fc-day-number">' + day + '</a><a class="fc-day-festival">中秋节</a></div>';
    }

    // 显示事件
    calendarHTML += '<div class="fc-day-events">'
    // 1.固定事件
    if(day == 5 || day == 6){
      calendarHTML += '<div class="fc-event fc-day-events-main">aaa</div>';
    }
    // 2.日程
    var sCheduleEvents = [
      {title:'aaa',time:'09:20'},
      {title:'bbb',time:'19:00'},
      {title:'ccc',time:'13:30'},
    ];
    $.each(sCheduleEvents,function(index,item){
      calendarHTML += '<a class="fc-event fc-day-events-calendar">'
                        +'<div class="fc-day-events-calendar-dot"></div>'
                        +'<div class="fc-day-events-calendar-time">'+item['time']+'</div>'
                        +'<div class="fc-day-events-calendar-title">'+item['title']+'</div>'
                    +'</a>';
    });
    calendarHTML += '</div></td>';
  }
 
  // 补充空白天数
  var lastDay = new Date(year, month, daysInMonth).getDay();
  for (var i = lastDay; i < 6; i++) {
    calendarHTML += '<td></td>';
  }
 
  calendarHTML += '</tr></tbody>';
  calendarHTML += '</table>';
 
  return calendarHTML;
}
</script>