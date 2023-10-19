<html>
    <head>
        <title>问卷信息</title>
    </head>
    <style>
        h1{
            text-align: center;
        }
        table{
            border:1px solid gray;
            margin:0 auto;
            /*border: none;*/
        }
        td {
            width:180px;
            /*border:1px solid black;*/
            text-align: center;
        }
        .tr1{
            height:40px;
            font-size:18px;
            font-weight:bold;
            background-color: #d9d2e9;
        }
        .tr2{
            background-color: #c9daf8;
        }
    </style>
    <body>
        <h1>大学生生问卷调查汇总</h1>
        <table>
            <tr class="tr1">
                <td style="width: 50px;">序号</td>
                <!--<td>标识</td>-->
                <td>生活费用</td>
                <td>生活来源</td>
                <td>性别</td>
                <td>年级</td>
                <td>消费途径</td>               
                <td>操作</td>
            </tr>
            <?php 
                include("conn.php");
                $sql2 = "SELECT * FROM `form`";
                $query = mysqli_query($db, $sql2);
                $i = 0;
                while($info= mysqli_fetch_array($query)){
                    $i++;
                echo "
            <tr class='tr2'>
                <td style='width: 50px;'>{$i}</td>
                <td>{$info['ques1']}</td>
                <td>{$info['ques2']}</td>
                <td>{$info['ques5']}</td>
                <td>{$info['ques3']}</td>
                <td>{$info['ques4']}</td>   
                <td><a href='form_info.php?id={$info['id']}'>查看</a></td>
            </tr>";
            }
            ?>
        </table>
    </body>
</html>

