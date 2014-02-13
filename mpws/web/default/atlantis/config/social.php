

    $default['SOCIAL'] = array();
    
    
    
   
    // socials
    
    $default['SOCIAL']['Facebook'] = array(
        'action' => 'http://www.facebook.com/sharer.php?u=%s&amp;t=%s',
        'title' => 'Facebook',
        'alt' => 'Share this on Facebook',
        'name' => 'Facebook'
    );
    $default['SOCIAL']['LinkedIn'] = array(
        'action' => 'http://www.linkedin.com/shareArticle?mini=true&amp;url=%s&amp;title=%s&amp;ro=false&amp;summary=&amp;source=',
        'title' => 'LinkedIn',
        'alt' => 'Post to LinkedIn',
        'name' => 'LinkedIn'
    );
    $default['SOCIAL']['GoogleBookmark'] = array(
        'action' => 'http://www.google.com/bookmarks/mark?op=add&amp;bkmk=%s&amp;title=%s',
        'title' => 'Add this to Google Bookmarks',
        'alt' => 'Add this to Google Bookmarks',
        'name' => 'GoogleBookmark'
    );
    $default['SOCIAL']['Myspace'] = array(
        'action' => 'http://www.myspace.com/Modules/PostTo/Pages/?u=%s&amp;t=%s',
        'title' => 'Post this to MySpace',
        'alt' => 'Post this to MySpace',
        'name' => 'Myspace'
    );
    $default['SOCIAL']['Twitter'] = array(
        'action' => 'http://twitter.com/home?status=%3$s  %1$s',
        'title' => 'Tweet This!',
        'alt' => 'Tweet This!',
        'name' => 'Twitter'
    );
    
    //social informers
    $default['SOCIAL']['I_FacebookLike'] = array(
        'title' => 'FacebookLike',
        'script' => '
            <div class="fb-like" data-send="true" data-width="500" data-show-faces="false" data-font="arial"></div>
        '
    );
    $default['SOCIAL']['I_Twitter'] = array(
        'title' => 'Twitter',
        'script' => '
            <a href="https://twitter.com/share" class="twitter-share-button" data-lang="ru">Tweet</a>
            <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
        '
    );
    $default['SOCIAL']['I_GooglePlusOne'] = array(
        'title' => 'GooglePlusOne',
        'script' => '
            <div class="g-plusone" data-size="tall" data-annotation="inline"></div>
        '
    );
    $default['SOCIAL']['I_LinkedIn'] = array(
        'title' => 'LinkedIn',
        'script' => '
            <script src="http://platform.linkedin.com/in.js" type="text/javascript"></script>
            <script type="IN/Share" data-counter="right"></script>
        '
    );
    $default['SOCIAL']['I_StumbleUpon'] = array(
        'title' => 'StumbleUpon',
        'script' => '
            <script src="http://www.stumbleupon.com/hostedbadge.php?s=2"></script>
        '
    );
    $default['SOCIAL']['I_Digg'] = array(
        'title' => 'Digg',
        'script' => '
            <script type="text/javascript">
            (function() {
            var s = document.createElement("SCRIPT"), s1 = document.getElementsByTagName("SCRIPT")[0];
            s.type = "text/javascript";
            s.async = true;
            s.src = "http://widgets.digg.com/buttons.js";
            s1.parentNode.insertBefore(s, s1);
            })();
            </script>
            <a class="DiggThisButton DiggWide"></a>
        '
    );
    
    // social to groups
    
    $default['SOCIAL']['GROUPS'] = array(
        'SHORT' => array(
            $default['SOCIAL']['Facebook'],
            $default['SOCIAL']['Twitter'],
            $default['SOCIAL']['Myspace']
        ),
        'FULLPROD' => array(),
        'FULL' => array(
            $default['SOCIAL']['Facebook'],
            $default['SOCIAL']['Twitter'],
            $default['SOCIAL']['Myspace'],
            $default['SOCIAL']['LinkedIn'],
            $default['SOCIAL']['GoogleBookmark']
        ),
        'I_SHORT' => array(
            $default['SOCIAL']['I_FacebookLike']
        ),
        'I_FULL' => array(
            $default['SOCIAL']['I_FacebookLike'],
            $default['SOCIAL']['I_Twitter'],
            $default['SOCIAL']['I_GooglePlusOne'],
            $default['SOCIAL']['I_LinkedIn'],
            $default['SOCIAL']['I_StumbleUpon'],
            $default['SOCIAL']['I_Digg']
        ),
        'I_FULLPROD' => array(
            $default['SOCIAL']['I_FacebookLike'],
            $default['SOCIAL']['I_Twitter'],
            $default['SOCIAL']['I_GooglePlusOne']
        )
    );
    
    // groups to pages
    $default['SOCIAL']['BOOKMARKS'] = array(
        'PAGE' => $default['SOCIAL']['GROUPS']['SHORT'],
        'PRODUCT_PAGE' => $default['SOCIAL']['GROUPS']['FULLPROD']
    );

    // groups to pages
    $default['SOCIAL']['INFORMERS'] = array(
        'PAGE' => array(),
        'PRODUCT_PAGE' => $default['SOCIAL']['GROUPS']['I_FULLPROD']
    );

    // polls
    $default['SOCIAL']['POLL_FORMS'] = array(
        'c2f0039b9a0105fc0d3a9c22ea24b1c0' => array(
            'title' => 'GOOGLE',
            'link' => '/service/remote.html?url=https://docs.google.com/spreadsheet/viewform?formkey=dDhDY3JFS2xRV1FyN3h6d2l5aEVHRHc6MA',
            'test1' => '/shop/poll_c2f0039b9a0105fc0d3a9c22ea24b1c0.html',
            'test' => 'https://docs.google.com/spreadsheet/viewform?formkey=dDhDY3JFS2xRV1FyN3h6d2l5aEVHRHc6MA'
        )
    
    );
    $default['SOCIAL']['POLLS'] = array(
        //'PRODUCT' => $default['SOCIAL']['POLL_FORMS']['c2f0039b9a0105fc0d3a9c22ea24b1c0']
    
    );

