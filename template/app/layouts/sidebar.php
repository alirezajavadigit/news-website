<div class="col-lg-4">
    <div class="sidebars-area">
        <div class="single-sidebar-widget editors-pick-widget">
            <h6 class="title">انتخاب سردبیر</h6>
            <?php foreach ($selectedPosts as $selectedPost) { ?>
                <div class="editors-pick-post">
                    <div class="feature-img-wrap relative">
                        <div class="feature-img relative">
                            <div class="overlay overlay-bg"></div>
                            <img class="img-fluid" src="<?= asset($selectedPost['image']) ?>" alt="">
                        </div>
                        <ul class="tags">
                            <li><a href="<?= url("show/category/" . $selectedPost['category_id']) ?>"><?= $selectedPost['category_name'] ?> </a></li>
                        </ul>
                    </div>
                    <div class="details">
                        <a href="<?= url("show/post/" . $selectedPost['id']) ?>">
                            <h4 class="mt-20"><?= $selectedPost['title'] ?> </h4>
                        </a>
                        <ul class="meta">
                            <li><a href="#"><span class="lnr lnr-user"></span><?= $selectedPost['user_name'] ?> </a></li>
                            <li><a href="#"><?= jalaliDate($selectedPost['created_at']) ?> <span class="lnr lnr-calendar-full"></span></a></li>
                            <li><a href="#"><?= $selectedPost['comment_count'] ?> <span class="lnr lnr-bubble"></span></a></li>
                        </ul>
                        <p class="excert">
                            <?= substr($selectedPost['summary'], 0, 87) ?>
                        </p>
                    </div>
                </div>
            <?php } ?>
        </div>
        <div class="single-sidebar-widget ads-widget">
            <img class="img-fluid" src="img/sidebar-ads.jpg" alt="">
        </div>
        <div class="single-sidebar-widget most-popular-widget">
            <h6 class="title">پر بحث ترین ها</h6>
            <?php foreach ($mostCommentedPosts as $mostCommentedPost) { ?>
                <div class="single-list flex-row d-flex">
                    <div class="thumb">
                        <img src="<?= asset($mostCommentedPost['image']) ?>" alt="<?= $mostCommentedPost['title'] ?>" width="100px" height="100px">
                    </div>
                    <div class="details">
                        <a href="<?= url("show/post/" . $mostCommentedPost['id']) ?>">
                            <h6><?= $mostCommentedPost['title'] ?></h6>
                        </a>
                        <ul class="meta">
                            <li><a href="#"><?= jalaliDate($mostCommentedPost['created_at']) ?><span class="lnr lnr-calendar-full"></span></a></li>
                            <li><a href="#"><?= $mostCommentedPost['comment_count'] ?><span class="lnr lnr-bubble"></span></a></li>
                        </ul>
                    </div>
                </div>
            <?php } ?>

        </div>

    </div>
</div>