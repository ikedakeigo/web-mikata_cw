 <?php 
        if( function_exists( "enqueue_font_awesome") ):
             add_action( 'wp_enqueue_scripts', 'enqueue_font_awesome' );
             function enqueue_font_awesome() {
                 wp_enqueue_style( 'font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css' );
             }
         endif;
     $url_encode = urlencode( get_permalink() );
     $title_encode = urlencode( get_the_title() ); 
     $twitter_account = '貴方のツイッターアカウント';
     ?>
     <ul class="sns clearfix">
         <li class="twitter">
             <a href="https://twitter.com/intent/tweet?url=<?php echo $url_encode; ?>&text=<?php echo $title_encode . urlencode( ' | ' ); echo urlencode( get_bloginfo('name')); ?>&via=<?php echo $twitter_account; ?>&tw_p=tweetbutton&related="<?php echo $twitter_account; ?>" onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;"><i class="fa fa-twitter"></i><span class="pc">ツイート</span></a>
         </li>
         <li class="facebook">
             <a href="https://www.facebook.com/sharer.php?src=bm&u=<?php echo $url_encode;?>&t=<?php echo $title_encode;?>" onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;"><i class="fa fa-facebook"></i><span class="pc">シェア</span><span class="share-count"><?php if(function_exists('get_scc_facebook')) { echo scc_get_share_facebook();}?></span></a>
         </li>
         <li class="googleplus">
             <a href="https://plus.google.com/share?url=<?php echo $url_encode;?>" onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;"><i class="fa fa-google-plus"></i><span class="pc">Google+</span><span class="share-count"><?php if(function_exists('get_scc_gplus')) { echo scc_get_share_gplus();}?></span></a>
         </li>
         <li class="hatebu">       
             <a href="https://b.hatena.ne.jp/add?mode=confirm&url=<?php echo $url_encode ?>" onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;"><span class="hatena-icon">B!</span><span class="pc">はてブ</span><span class="share-count"><?php if(function_exists('get_scc_hatebu')) { echo scc_get_share_hatebu();}?></span></a>
         </li>
     </ul>