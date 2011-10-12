<?php sleep(1);?>
<?php $category = array('News', 'Polityka', 'Biznes', 'Sport', 'Kultura', 'Technika', 'Publicystyka', 'Zdrowie'); ?>
<?php foreach($category as $item) :?>
<div class="news_item">
        <div class="news_item_main">
            <div class="news_item_img img_<?php echo strtolower($item)?>"></div>
            <div class="news_item_text">
                <h3>Przykładowy tytuł jakiegokolwiek artykułu na okolo 60 znaków</h3>
                <p>przykładowa treść artykułu, pierwsze 3 linijki, przykładowa treść artykułu, pierwsze 3 linijki, przykładowa treść artykułu, pierwsze 3 linijki, przykładowa treść artykułu, pierwsze 3 linijki, przykładowa treść artykułu, pierwsze 3 linijki, przykładowa treść artykułu, pierwsze 3 linijki, przykładowa treść artykułu, pierwsze 3 linijki...</p>
            </div>
        </div>
        <div class="news_item_bottom">
            <span class="vote">
                <img src="/img/1_37.png" style="position: relative; left: <?php echo rand( 3, 70); ?>px"/>
            </span>
            <span class="bar corners">
                <span class="news_item_date info">12.12.2011r</span>
                <span class="news_item_pkt info">+235pkt</span>
                <span class="news_item_pos info">135 miejsce</span>
                <span class="news_item_user info">DonPedro</span>
                <span class="news_item_more"><a class="corners" href="#more">czytaj dalej >></a></span>
                <span class="news_item_category"><a class="corners <?php echo strtolower($item)?>" href="#category"><?php echo strtolower($item)?></a></span>
            </span>
        </div>
    </div>
    <hr />
  <?php endforeach; ?>