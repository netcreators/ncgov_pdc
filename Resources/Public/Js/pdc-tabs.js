/*
 * Get page referrer
 */
function getReferrer() {
	var referrer = '';
	try {
		referrer = window.top.document.referrer;
	} catch (e) {
		if (window.parent) {
			try {
				referrer = window.parent.document.referrer;
			} catch (e2) {
				referrer = '';
			}
		}
	}
	if (referrer === '') {
		referrer = document.referrer;
	}
	return referrer;
}

jQuery(document).ready(function($){

    var parseUrl = function (url) {
        // Source: http://www.abeautifulsite.net/parsing-urls-in-javascript/

        var parser = document.createElement('a'),
            searchObject = {},
            queries, split, i;
        // Let the browser do the work
        parser.href = url;

        // Convert query string to object
        queries = parser.search.replace(/^\?/, '').split('&');
        for( i = 0; i < queries.length; i++ ) {
            split = queries[i].split('=');
            searchObject[split[0]] = split[1];
        }
        return {
            protocol: parser.protocol,
            host: parser.host,
            hostname: parser.hostname,
            port: parser.port,
            pathname: parser.pathname,
            search: parser.search,
            searchObject: searchObject,
            hash: parser.hash.replace(/^#/,'')
        };
    };


	$('.ncel-tabcontrol-container .ncel-tabcontrol-tab').click(function(event) {
		$('.ncel-tabcontrol-container ul.ncel-tabcontrol-tabs li').each(function() {
			$(this).removeClass('ncel-tabcontrol-tab-active');
		});
		$(this).parent().addClass('ncel-tabcontrol-tab-active');
		// hide all content
		$('.ncel-tabcontrol-container .ncel-tabcontrol-content').hide();
		// show active
		var target = event.target.toString();
		target = target.split('#');
		$('#' + target[1]).fadeIn();
		event.preventDefault();
	});
	$('.ncel-tabcontrol-contents').css('height', 'auto');
	var maxHeight = 0;
	$('.ncel-tabcontrol-container .ncel-tabcontrol-content').each(function () {
		if( $(this).outerHeight(true) > maxHeight) {
			maxHeight = $(this).outerHeight(true);
		}
	});
	$('.ncel-tabcontrol-contents').height(maxHeight * 1.03 + 30);
	$('.ncel-tabcontrol-contents .ncel-tabcontrol-content').each(function () {
		$(this).css('min-height', maxHeight);
		//$(this).css('height', maxHeight); will break IE7
	});
	$('.ncel-tabcontrol-container .ncel-tabcontrol-content').hide();
	$('.ncel-tabcontrol-container .ncel-tabcontrol-content:first').show();
	$('.ncel-tabcontrol-container .ncel-tabcontrol-tabs li:first').addClass('ncel-tabcontrol-tab-active');



    // Auto-activating PDC tabs via URL hash or "/tab/{tab-name}/" path segments

    var urlParts = parseUrl(window.location.href);
    var activeTabClass = 'ncel-tabcontrol-tab-active';

    // Open PDC tab via URL anchor

    if(!urlParts.hash) {

        // show first, default
        $('.ncel-tabcontrol-container .ncel-tabcontrol-content').hide();
        $('.ncel-tabcontrol-container .ncel-tabcontrol-content:first').show();
        $('.ncel-tabcontrol-container .ncel-tabcontrol-tabs li:first').addClass(activeTabClass);

    } else {

        // Set all tabs inactive
        $('.ncel-tabcontrol-container ul.ncel-tabcontrol-tabs li').each(function() {
            $(this).removeClass(activeTabClass);
        });
        // Set tab referencing the requested tab content active
        $('.ncel-tabcontrol-container ul.ncel-tabcontrol-tabs a').each(function() {
            if($(this).attr('href').indexOf('#' + urlParts.hash) >= 0) {
                $(this).parent().addClass(activeTabClass);
            }
        });

        // Hide all tab content
        $('.ncel-tabcontrol-container .ncel-tabcontrol-content').hide();
        // Show requested tab content
        $('#' + urlParts.hash).show();
    }

    // Open PDC tab via path segment /tab/{tab-name}

    // Replace possible trailing slash before splitting into segments
    var pathSegments = urlParts.pathname.replace(/\/$/,'').split('/');
    var pathSegmentCount = pathSegments.length;

    if((pathSegments[pathSegmentCount-2] == "tab") && (pathSegments[pathSegmentCount-1])) {
        var tabSelector = '.tab-'+pathSegments[pathSegmentCount-1];
        var $targetTab = $(tabSelector);

        if($targetTab != undefined && $targetTab != null) {

            // Deactivate all tabs
            $('.ncel-tabcontrol-container ul.ncel-tabcontrol-tabs li').removeClass(activeTabClass);

            // Activate target tab
            $targetTab.addClass(activeTabClass);
            var tabHref = $(tabSelector +' a.ncel-tabcontrol-tab').attr('href');
            if(tabHref) {
                var targetContentId = parseUrl(tabHref).hash;
                // Hide all tab content
                $('.ncel-tabcontrol-container .ncel-tabcontrol-content').hide();
                // Show target tab content
                $('#' + targetContentId).show();
            }
        }
    }



	// make search input element use the title tag as default value when empty
	var searchDefaultValue = $('.pdc-search-input').val();
	if(searchDefaultValue == '') {
		$('.pdc-search-input').val($('.pdc-search-input').attr('title'));
	}
	var clearMePrevious = '';
	// clear input on focus
	$('.pdc-search-input').focus(function() {
		if($(this).val() == $(this).attr('title')) {
			clearMePrevious = $(this).val();
			$(this).val('');
		}
	});

	// if field is empty afterward, add text again
	$('.pdc-search-input').blur(function() {
		if($(this).val() == '') {
			$(this).val(clearMePrevious);
		}
	});
});
