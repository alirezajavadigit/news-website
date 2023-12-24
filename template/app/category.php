<?php require_once "template/app/layouts/header.php" ?>

<div class="site-main-container">
        <!-- Start top-post Area -->
        <section class="top-post-area pt-10">
            <div class="container no-padding">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="hero-nav-area">
                            <h1 class="text-white">اخبار دسته بندی <?= $category['name'] ?></h1>

                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="news-tracker-wrap">
                            <h6><span>Breaking News:</span> <a href="#">Astronomy Binoculars A Great Alternative</a></h6>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- End top-post Area -->
        <!-- Start latest-post Area -->
        <section class="latest-post-area pb-120">
            <div class="container no-padding">
                <div class="row">
                    <div class="col-lg-8 post-list">
                        <!-- Start latest-post Area -->
                        <div class="latest-post-wrap">
                        <h4 class="cat-title">آخرین اخبار</h4>
                        <?php foreach ($lastsixPosts as $lastsixPost) { ?>
                            <div class="single-latest-post row align-items-center">
                                <div class="col-lg-5 post-left">
                                    <div class="feature-img relative">
                                        <div class="overlay overlay-bg"></div>
                                        <img class="img-fluid" src="<?= asset($lastsixPost['image']) ?>" alt="">
                                    </div>
                                    <ul class="tags">
                                        <li><a href="<?= url("show/category/" . $lastsixPost['category_id']) ?>"><?= $lastsixPost['category_name'] ?></a></li>
                                    </ul>
                                </div>
                                <div class="col-lg-7 post-right">
                                    <a href="<?= url("show/post/" . $lastsixPost['id']) ?>">
                                        <h4><?= $lastsixPost['title'] ?></h4>
                                    </a>
                                    <ul class="meta">
                                        <li><a href="#"><span class="lnr lnr-user"></span><?= $lastsixPost['user_name'] ?></a></li>
                                        <li><a href="#"><?= jalaliDate($lastsixPost['created_at']) ?><span class="lnr lnr-calendar-full"></span></a></li>
                                        <li><a href="#"><?= $lastsixPost['comment_count'] ?><span class="lnr lnr-bubble"></span></a></li>
                                    </ul>
                                    <p class="excert">
                                        <?= substr($lastsixPost['summary'], 0, 70) ?>
                                    </p>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                        <!-- End latest-post Area -->

                        <!-- Start banner-ads Area -->
                        <div class="col-lg-12 ad-widget-wrap mt-30 mb-30">
                            <img class="img-fluid" src="img/banner-ad.jpg" alt="">
                        </div>
                        <!-- End banner-ads Area -->
                        <!-- Start popular-post Area -->
                        <div class="popular-post-wrap">
                        <h4 class="title">اخبار پربازدید</h4>
                        <?php if (isset($mostViewedPosts[0])) { ?>
                            <div class="feature-post relative">
                                <div class="feature-img relative">
                                    <div class="overlay overlay-bg"></div>
                                    <img class="img-fluid" src="<?= asset($mostViewedPosts[0]['image']) ?>" alt="">
                                </div>
                                <div class="details">
                                    <ul class="tags">
                                        <li><a href="<?= url("show/category/" . $mostViewedPosts[0]['category_id']) ?>"><?= $mostViewedPosts[0]['category_name']?></a></li>
                                    </ul>
                                    <a href="<?= url("show/post/" . $mostViewedPosts[0]['id']) ?>">
                                        <h3><?= $mostViewedPosts[0]['title']?></h3>
                                    </a>
                                    <ul class="meta">
                                        <li><a href="#"><span class="lnr lnr-user"></span><?= $mostViewedPosts[0]['user_name'] ?></a></li>
                                        <li><a href="#"><?= jalaliDate($mostViewedPosts[0]['created_at']) ?><span class="lnr lnr-calendar-full"></span></a></li>
                                        <li><a href="#"><?= $mostViewedPosts[0]['comment_count'] ?><span class="lnr lnr-bubble"></span></a></li>
                                    </ul>
                                </div>
                            </div>
                        <?php } ?>

                        <div class="row mt-20 medium-gutters">
                            <?php if (isset($mostViewedPosts[1])) { ?>

                                <div class="col-lg-6 single-popular-post">
                                    <div class="feature-img-wrap relative">
                                        <div class="feature-img relative">
                                            <div class="overlay overlay-bg"></div>
                                            <img class="img-fluid" src="<?= asset($mostViewedPosts[1]['image']) ?>" alt="">
                                        </div>
                                        <ul class="tags">
                                            <li><a href="<?= url("show/category/" . $mostViewedPosts[1]['category_id']) ?>"><?= $mostViewedPosts[1]['category_name']?> </a></li>
                                        </ul>
                                    </div>
                                    <div class="details">
                                    <a href="<?= url("show/post/" . $mostViewedPosts[1]['id']) ?>">
                                        <h3><?= $mostViewedPosts[1]['title']?></h3>
                                    </a>
                                    <ul class="meta">
                                        <li><a href="#"><span class="lnr lnr-user"></span><?= $mostViewedPosts[1]['user_name'] ?></a></li>
                                        <li><a href="#"><?= jalaliDate($mostViewedPosts[1]['created_at']) ?><span class="lnr lnr-calendar-full"></span></a></li>
                                        <li><a href="#"><?= $mostViewedPosts[1]['comment_count'] ?><span class="lnr lnr-bubble"></span></a></li>
                                    </ul>
                                        <p class="excert">
                                        <?= substr($mostViewedPosts[1]['summary'], 0, 70) ?>
                                        </p>
                                    </div>
                                </div>
                            <?php } ?>
                            <?php if (isset($mostViewedPosts[2])) { ?>

                                <div class="col-lg-6 single-popular-post">
                                    <div class="feature-img-wrap relative">
                                        <div class="feature-img relative">
                                            <div class="overlay overlay-bg"></div>
                                            <img class="img-fluid" src="<?= asset($mostViewedPosts[2]['image']) ?>" alt="">
                                        </div>
                                        <ul class="tags">
                                            <li><a href="<?= url("show/category/" . $mostViewedPosts[2]['category_id']) ?>"> <?= $mostViewedPosts[2]['category_name']?></a></li>
                                        </ul>
                                    </div>
                                    <div class="details">
                                    <a href="<?= url("show/post/" . $mostViewedPosts[2]['id']) ?>">
                                        <h3><?= $mostViewedPosts[2]['title']?></h3>
                                    </a>
                                    <ul class="meta">
                                        <li><a href="#"><span class="lnr lnr-user"></span><?= $mostViewedPosts[2]['user_name'] ?></a></li>
                                        <li><a href="#"><?= jalaliDate($mostViewedPosts[2]['created_at']) ?><span class="lnr lnr-calendar-full"></span></a></li>
                                        <li><a href="#"><?= $mostViewedPosts[2]['comment_count'] ?><span class="lnr lnr-bubble"></span></a></li>
                                    </ul>
                                        <p class="excert">
                                        <?= substr($mostViewedPosts[2]['summary'], 0, 70) ?>
                                    </div>
                                </div>
                            <?php } ?>

                        </div>
                    </div>
                        <!-- End popular-post Area -->
                    </div>
                    <?php require_once "template/app/layouts/sidebar.php" ?>
                </div>
            </div>
        </section>
        <!-- End latest-post Area -->
    </div>
                
<?php require_once "template/app/layouts/footer.php" ?>
