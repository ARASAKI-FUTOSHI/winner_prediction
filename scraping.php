<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Insert title here</title>
</head>
<body>
<?php 
    //ここからフィールド↓
    $list; //試合のリスト日付のくくり（".cp-game-list"）
    $ttl; //日付（".c-gradationLineTitle__ttl"）
    $league_period; //B1かB2と第〇節か
    $league; //B1かB2（".cp-game-list__ttl__txt"）
    $period; //第〇節（"#period"）1
    $game; //直近の試合 (".cp-game-panel") 
    $hmTeam; // ホームチーム("#abbHmTeamNm") 
    $awTeam; //アウェイチーム("#abbAwTeamNm") 
    $time;  //開始時間("#gameDt") 
    $stadium; //会場("#stadium") 
    $game_title_num; //$list内にあるタイトル(B1orB2)の表記の数
    $view_game_count; //同日にB1とB2の試合があった場合のB1の試合数をカウントする
    $view_geme = 0; ////該当試合の有無を確認
   
    //ここまでフィールド↑
    
    // PHP Simple HTML DOM Parser の読み込み
    require_once ("../PHP_library/simple_html_dom.php");
    // URLをオブジェクト化
     $url = "https://store.toto-dream.com/dcs/subos/screen/pi36/spin054/PGSPIN05401InitGameSchedule.form"; //公式ページ
//    $url = "https://store.toto-dream.com/dcs/subos/screen/pi34/spin052/PGSPIN05201InitGameSchedule.form"; //サッカー
//     $url = "./winner_prediction_main10_24.html";//模擬用
    $html = file_get_html($url);
    $list = $html->find( '.cp-game-list' ); //試合のリスト 配列で取得

    
    for($i=0; $i < count($list);$i++){ //$listの配列分ループ
        $league = $list[$i]->find(".cp-game-list__ttl__txt");//B1かB2取得
        $period = $list[$i]->find("#period");//第〇節 配列で取得
        $ttl = $list[$i]->find(".c-gradationLineTitle__ttl");//日付 配列で取得
        $hmTeam = $list[$i]->find("#abbHmTeamNm"); //$list[$i]内のホームチーム名を配列で取得
        $awTeam = $list[$i]->find("#abbAwTeamNm"); //$list[$i]内のアウェイチーム名を配列で取得
        $time = $list[$i]->find("#gameDt"); //$list[$i]内の試合開始時間を配列で取得
        $stadium = $list[$i]->find("#stadium"); //$list[$i]内の試合会場を配列で取得
        //↓$list内にあるタイトル(B1orB2)の表記と試合内容を配列で取得↓
        $game_and_title_list = $list[$i]->find("div.cp-game-list__ttl--cntr , div.cp-game-list--cntr");
        $game_title_num = 0; //0をセット
        $view_game_count = 0; //0をセット
        //----------------ここから--
        //同日にB1とB2の試合があった時にB1の試合数を数える
        for($j = 0; $j < count($game_and_title_list); $j++){ 
            if($game_and_title_list[$j]->class === "cp-game-list__ttl--cntr"){ 
                $game_title_num++; //"cp-game-list__ttl--cntr"をカウント
            }
            if($game_title_num < 2){ 
                $view_game_count++; //B1の試合をカウント
            }
//             echo $game_and_title_list[$j]->plaintext; //確認用
//             echo $view_game_count;  //確認用
//             echo "<br>";  //確認用
             
        }
        //-------------ここまで-----
          $view_game_count--;
        for($j=0 ; $j < 1;$j++){ //B2を使うときにcount($league)
            if( strpos($league[$j],"B1リーグ") != 0){ //$league[$j]にB1リーグと表記がある場合のみtrue
?>
                <table>
                <?php
                    $view_geme++; //該当試合の有無を確認
                    echo $ttl[0]->plaintext; //日付　$listと同数なので0でいい？
                ?><tr><th>
                <?php 
                    echo $period[$j]->plaintext;//節　->plaintextでタグを外して出力
                ?></th><th><?php 
                    echo $league[$j]->plaintext; //リーグ
                ?></th></tr>
                <?php 

                    for($k=0; $k < count($hmTeam);$k++){

                
                ?><tr><td colspan="2"><?php
                    echo $hmTeam[$k]->plaintext;
                    echo $awTeam[$k]->plaintext;
                    //echo $list[$i]->find("#abbHmTeamNm" , $k)->plaintext;
                    //echo $list[$i]->find("#abbAwTeamNm" , $k)->plaintext;
                    if($view_game_count-1 <= $k){
                        $view_game_count = 0;
                        $game_title_num = 0;
                        break;
                    } 
                }
                ?></td></tr>
                </table>
                <br>
                <?php 
            }
        }  
    }
    if($view_geme === 0){
        echo "<p>該当試合がありません</p>";
    }
    $html->clear();
?>
</body>
</html>