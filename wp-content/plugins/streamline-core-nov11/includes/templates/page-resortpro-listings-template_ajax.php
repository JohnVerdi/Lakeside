    <?php foreach ($data['data'] as $d): ?>
        <div class="col-md-4">
            <div class="thumb">
                <header class="thumb-header">
                    <a class="hover-img"
                       href="<?php echo '/'.$d['id'] ?>">

                        <img width="360" height="270"
                             src="<?php echo $d['default_thumbnail_path'] ?>"
                             class="attachment-360x270 size-360x270 wp-post-image" alt="img41"
                             srcset="<?php echo $d['default_thumbnail_path'] ?>"
                             sizes="(max-width: 360px) 100vw, 360px">                <h5
                                class="hover-title-center">More Details </h5>
                    </a>
                </header>
                <div class="thumb-caption">
                    <?php if($d['rating_average'] > 0): ?>
                        <ul class="icon-group text-color">
                            <?php for ($i=20;$i<=100;$i=$i+20): ?>
                                <li><i class="fa  fa-star<?php echo $d['rating_average']>$i?'':'-o' ?>"></i></li>
                            <?php endfor; ?>
                        </ul>
                    <?php else: ?>
                        <span>No rating</span>
                    <?php endif; ?>
                    <h5 class="thumb-title"><a class="text-darken"
                                               href="<?php echo '/'.$d['id'] ?>"> <?php echo $d['name'] ?></a></h5>
                    <p class="mb0">
                        <small><i class="fa fa-map-marker"></i><?php echo $d['location_area_name'] .', '.$d['location_name'] ?>
                        </small>
                    </p>
                    <div class="text-darken">
                    </div>
                    <p class="mb0 text-darken">

                        <span class="text-lg lh1em">$<?php echo $d['price_data'] ?>.00 /night</span>
                    </p>
                    <a class="btn-fav" data-hotel="<?php echo $d['id']; ?>" data-toggle="tooltip" data-placement="right" title="">
                        <i class="fa <?php echo in_array($d['id'], $fav)? 'fa-heart': 'fa-heart-o'?>"></i>
                    </a>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
