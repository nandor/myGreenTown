if(typeof(oldIE) == 'undefined') var oldIE = false;

$(document).ready(function() {
    // detect browser
    var browser = (navigator.userAgent) ? navigator.userAgent : '';
    if(typeof(clrIE) == 'boolean')
    {
        browser = 'ie';
    }
    else
    {
        browser = (browser.indexOf('Opera') >= 0) ? (
            (browser.indexOf('Opera Mini/') > 0) ? 'opera-mini' : 'opera') : (
            (browser.indexOf('Gecko/') > 0) ? 'mozilla' : (
                (browser.indexOf('WebKit/') > 0) ? 'webkit' : (
                    (browser.indexOf('MSIE') > 0) ? 'ie' : 'unknown'
                )
            )
        );
    }
    $('body').addClass('browser-' + browser + ((oldIE) ? ' old-ie transform' : ''));

    // transformations and test browser
    if(!oldIE)
    {
        var test = document.createElement('canvas'),
            found = false,
            list = new Array('MozTransform', 'webkitTransform', 'OTransform', 'transform', 'msTransform');
        for(var i=0; i<list.length; i++)
        {
            if(typeof(test.style[list[i]]) != 'undefined') found = true;
        }
        if(found) $('body').addClass('can-transform');
        if(test.style['textShadow'] != 'undefined') $('body').addClass('has-shadows');
        delete test;
            
        setTimeout("$('body').addClass('transform');", 2000);
        $(window).load(function() { $('body').addClass('transform'); });
    }

    // navigation
    $('div.nav-extra').not('div.nav-extra-footer').each(function()
    {
        var count = 0;
        $(this).find('a').each(function() {
            if(count > 0) $(this).before(' &bull; ');
            count ++;
        });
        if(!count) $(this).css('display', 'none');
    });
    
    $('#footer div.nav-links > a').each(function(i)
    {
        if(i > 0) $(this).before(' &bull; ');
    });
    
    // clear divs
    $('#page-body, #footer').append('<div class="clear"></div>');
    $('.cp-mini:last').after('<div class="clear"></div>');
    
    // remove extra lines
    $('#page-body > hr, #cp-main > hr, #page-body > form > hr').remove();
    
    // unread posts
    $('dl.icon').each(function()
    {
        var bg = $(this).css('background-image');
        if(bg.length && bg.indexOf('_unread') > 0)
        {
            $(this).parents('li:first').addClass('unread');
        }
        else if(bg.length && bg.indexOf('forum_link') > 0)
        {
            $(this).parents('li:first').addClass('forum-link');
        }
    });
    
    // topic title
    $('body.section-viewtopic #page-body > h2:first').addClass('title');
    
    // index: reported/unapproved topics
    $('li.row a img').each(function()
    {
        if(this.src.indexOf('icon_topic_unapproved') > 0)
        {
            $(this).parents('li.row:first').addClass('unapproved');
        }
    });
    $('dd.lastpost a img').each(function()
    {
        if(this.src.indexOf('icon_topic_unapproved') > 0 || this.src.indexOf('icon_topic_reported') > 0)
        {
            var prev = $(this).parents('dl.icon:first').find('dt');
            if(!prev.length) return;
            if(!prev.find('div.extra').length)
            {
                prev.prepend('<div class="extra"></div>');
            }
            prev = prev.find('div.extra');
            $(this).parent('a').appendTo(prev);
        }
    });
    
    // remove rounded block within rounded block
    $('div.panel div.post, div.panel ul.topiclist, div.panel table.table1').parents('div.panel').addClass('panel-wrapper');
    
    // tabs
    $('#tabs, #navigation, #minitabs').each(function()
    {
        var last = false,
            count = 0;
        $('li', $(this)).each(function(i)
        {
            if(i == 0) $(this).addClass('first');
            last = $(this);
            count ++;
        });
        if(count < 2)
        {
            $(this).hide();
        }
        else
        {
            if(last !== false) last.addClass('last');
            $(this).find('hr').remove();
            $(this).parents('form').css('display', 'inline');
            $(this).append('<div class="clear"></div>');
            $(this).find('a').each(function()
            {
                if(!$('span', this).length)
                {
                    $(this).html('<span>' + $(this).html() + '</span>');
                }
            });
        }
    });
    $('#navigation').parents('.panel').removeClass('panel').addClass('cp-panel');
    
    // control panel: remove empty boxes
    $('#cp-main .panel').each(function()
    {
        var inner = $(this).find('.inner:first');
        if(!inner.length) return;
        if(inner.children().length < 2)
        {
            $(this).hide();
        }
    });
    
    // fix right side margin
    $('#page-body > p.rightside').each(function()
    {
        var next = $(this).next();
        if(next.is('p') && !next.hasClass('rightside')) next.css('margin-top', 0);
    });
    
    // pm post
    $('.post > div, .panel > div').addClass('inner');
    
    // emulate multiple backgrounds
    if(oldIE)
    {
        $('#header').wrapInner('<div class="hdr1"></div>');
        $('#footer').wrapInner('<div class="hdr1"><div class="hdr2"></div></div>');
        $('div.panel > .inner').addClass('inner-panel');
        $('div.forabg, div.forumbg, div.panel-wrapper').not('.cp-panel').addClass('old-ie-wrap-1').wrapInner('<div class="hdr1-1"><div class="hdr1-2"><div class="hdr1-3"><div class="hdr1-4"><div class="hdr1-5"></div></div></div></div></div>');
        $('div.post, .panel, .cp-mini, ul.topiclist li').not('.header, .panel-wrapper').addClass('old-ie-wrap-2').wrapInner('<div class="hdr2-1"><div class="hdr2-2"><div class="hdr2-3"><div class="hdr2-4"><div class="hdr2-5"><div class="hdr2-6"><div class="hdr2-last"></div></div></div></div></div></div></div>');
    }

    // search box
    $('div.search-box input').focus(function() { $(this).parents('.search-box').addClass('focus'); }).blur(function() { $(this).parents('.search-box').removeClass('focus'); })

    // header search box
    $('#search-box form').submit(function() { var value = $('#search-box input:text').val(); return (value == laSearchMini || value == '') ? false : true; });
    $('#search-box input:text').focus(function() { 
        if(this.value == laSearchMini) this.value = '';
        $('#search-box').addClass('focused');
    }).blur(function() { 
        if(this.value == '') this.value = laSearchMini;
        $('#search-box').removeClass('focused');
    });
});

$(window).load(function() {
    // set min width
    var min = 40;
    $('#nav-header a, #search-adv, #search-box').each(function()
    {
        min += $(this).width() + 20;
    });
    $('body').css('min-width', Math.min(
        Math.floor(min),
        Math.floor($('body').width())
        ) + 'px');
});
