angular.module('pageamp', ['angular.img'])
/**
 * Filters
 */
.filter('loadtime', ['$sce', function ($sce) {
    return function (ms) {
        if (typeof ms == 'number') {
            return (ms / 1000).toFixed(1) + 's';
        }

        return ms;
    }
}])
.filter('loadTimeLessThan', ['$sce', function ($sce) {
    return function (ms) {
        if (typeof ms == 'number' && ms > 0) {
            return Math.floor((ms / 1000).toFixed(1) - .1) + 's';
        }

        return ms;
    }
}])
.filter('size', ['$sce', function ($sce) {
    return function (bytes) {
        return $sce.trustAsHtml((bytes / 1000000).toFixed(1) + 'mb');
    }
}])
.filter('score', function () {
    return function (score, letterOnly) {
        return String.fromCharCode(65 + Math.floor((100 - (score + .5)) / 10)) + (!letterOnly ? ' (' + score + '%)' : '');
    }
})

/**
 * Controllers
 */
.controller('resultsCtrl', ['$scope', '$sce', '$http', '$filter', '$timeout', function($scope, $sce, $http, $filter, $timeout) {

    var ratings = [
        'Very Good',
        'Good',
        'Poor',
        'Very Poor'
    ];
    var resultDefinitions = {
        "3":{
            "text":"Avoid Plugins",
            "type":"Content",
            "priority":"Low"
        },
        "5":{
            "text":"Avoid document.write",
            "type":"JS",
            "priority":"Medium"
        },
        "9":{
            "text":"Improve server response time",
            "type":"Server",
            "priority":"Low"
        },
        "24":{
            "text":"Remove unused CSS",
            "type":"CSS",
            "priority":"Low"
        },
        "27":{
            "text":"Serve static content from a cookieless domain",
            "type":"Cookie",
            "priority":"High"
        },
        "31":{
            "text":"Specify a viewport for mobile browsers",
            "type":"Content",
            "priority":"Low"
        },
        "33":{
            "text":"Use an application cache",
            "type":"Content",
            "priority":"Low"
        },
        "34":{
            "text":"Use efficient CSS selectors",
            "type":"CSS",
            "priority":"Low"
        },
        "Sprite":{
            "text":"Combine images using CSS sprites",
            "type":"Images",
            "priority":"High"
        },
        "Gzip":{
            "text":"Enable gzip compression",
            "type":"Server",
            "priority":"High"
        },
        "InlineCSS":{
            "text":"Inline small CSS",
            "type":"CSS",
            "priority":"High"
        },
        "InlineJS":{
            "text":"Inline small JavaScript",
            "type":"JS",
            "priority":"High"
        },
        "CharsetEarly":{
            "text":"Specify a character set early",
            "type":"Content",
            "priority":"Medium"
        },
        "AvoidCharsetInMetaTag":{
            "type":"Content",
            "priority":"Low"
        },
        "AvoidLandingPageRedirects":{
            "type":"Server",
            "priority":"High"
        },
        "CssImport":{
            "type":"CSS",
            "priority":"Medium"
        },
        "BadReqs":{
            "type":"Content",
            "priority":"High"
        },
        "DeferParsingJavaScript":{
            "type":"JS",
            "priority":"High"
        },
        "EnableKeepAlive":{
            "type":"Server",
            "priority":"High"
        },
        "BrowserCache":{
            "type":"Server",
            "priority":"High"
        },
        "MinifyHTML":{
            "type":"Content",
            "priority":"Low"
        },
        "MinifyCSS":{
            "type":"CSS",
            "priority":"High"
        },
        "MinRedirect":{
            "type":"Content",
            "priority":"High"
        },
        "MinifyJS":{
            "type":"JS",
            "priority":"High"
        },
        "MinReqSize":{
            "type":"Content",
            "priority":"High"
        },
        "OptImgs":{
            "type":"Images",
            "priority":"High"
        },
        "CssJsOrder":{
            "type":"CSS/JS",
            "priority":"High"
        },
        "PreferAsync":{
            "type":"JS",
            "priority":"Medium"
        },
        "CssInHead":{
            "type":"CSS",
            "priority":"High"
        },
        "RemoveQuery":{
            "type":"Content",
            "priority":"Low"
        },
        "DupeRsrc":{
            "type":"Content",
            "priority":"High"
        },
        "ScaleImgs":{
            "type":"Images",
            "priority":"High"
        },
        "CacheValid":{
            "type":"Server",
            "priority":"High"
        },
        "VaryAE":{
            "type":"Server",
            "priority":"Low"
        },
        "ImgDims":{
            "type":"Images",
            "priority":"Medium"
        },
        "NA": {
            "type":"NA",
            "priority":"NA"
        }
    };

    $scope.pagespeedResults = [];
    $scope.pagespeedMobileResults = [];
    $scope.pagespeedScore = 0;
    $scope.pagespeedMobileScore = 0;
    $scope.pagespeedRating = ratings[ratings.length - 1];
    $scope.pagespeedMobileRating = ratings[ratings.length - 1];
    $scope.pageLoadTime = 0;
    $scope.pageMobileLoadTime = 0;
    $scope.pageBytes = 0;
    $scope.pageRequests = 0;
    $scope.pageMobileBytes = 0;
    $scope.pageMobileRequests = 0;
    $scope.testStatus = 'Starting';
    $scope.testing = 1;
    $scope.desktopScreenshot = '';
    $scope.mobileScreenshot = '';
    $scope.isDesktopTesting = false;
    $scope.isMobileTesting = false;
    $scope.desktopReportUrl = '';
    $scope.mobileReportUrl = '';
    $scope.url = '';
    $scope.testDate = moment().tz(moment.tz.guess()).format('h:m:s z, M/D/Y');
    $scope.nextAnalysisPage = false;

    $scope.$on('SOCKET:INITALIZED', function () {
        $scope.sendTest();
    });

    $scope.checkout = function (checkoutBaseUrl) {
        return window.location.href = checkoutBaseUrl + '&url=' + encodeURIComponent($scope.url);
    }

    $scope.sendTest = function (e) {
        var matches = [];
        if ((matches = /url=([^&\?]+)/.exec(window.location.search))) {
            $scope.url = decodeURIComponent(matches[1]);
        }
        console.log(matches);

        if ($scope.url) {
            jQuery.post('//' + location.hostname + '/wp-json/api/v1/test',{
                url: $scope.url,
                id: socket.id
            }).done(function (response) {
                if (response.success) {
                    var matches;
                    if (matches = $scope.url.match(/^(http(?:s)?:\/\/)?([\w.-]+(?:\.[\w\.-]+)+[\w\-\._~:\/?#[\]@!\$&'\(\)\*\+,;=.]+)$/)) {
                        $scope.url = (matches[1] || 'http://') + matches[2];
                    }
                    // Loading
                    startSocketIoListener();
                } else {
                    displayErrorPrompt(response.errors[0] + '. Please try again');
                    $scope.testStatus = 'Error';
                    window.animateAnalyze = false;
                    $scope.$apply('testStatus');
                }
            }).fail(function () {
                displayErrorPrompt('Failed to submit your request, please try again');
                $scope.testStatus = 'Error';
                window.animateAnalyze = false;
                $scope.$apply('testStatus');
            });
        } else {
            displayErrorPrompt('Please fill in the URL field');
            $scope.testStatus = 'Error';
            window.animateAnalyze = false;
        }
    }

    var getMobileLoadTimeFromDesktop = function () {
        var loadTime = $scope.pageLoadTime * 1.5;
        var secPaddings = {
            'A': -.5,
            'B': 1,
            'C': 2,
            'D': 5,
            'E': 8,
            'F': 10,
            'G': 13
        };

        loadTime += (loadTime + secPaddings[$filter('score')($scope.pagespeedScore, true)]);

        return loadTime;
    }

    var displayErrorPrompt = function (message) {
        alert(message);
        window.location.href = window.location.protocol + '//' + window.location.hostname;
    }

    var startSocketIoListener = function () {
        $scope.testStatus = 'Loading';
        $scope.isMobileTesting = true;
        $scope.isDesktopTesting = true;
        $scope.$apply('testStatus');

        console.log($scope.testStatus);
        socket.on(socketTestStatusEvent + ':' + socket.id, function (data) {
            var tmpData = data;
            console.log(tmpData);

            if (data.state == 'completed') {
                console.log(data);

                if (data.type == 'desktop') {
                    $scope.pagespeedScore = data.pagespeedScore;
                    $scope.pageLoadTime = data.pageLoadTime;
                    $scope.desktopReportUrl = data.reportUrl;
                    for (var x in data.resources.gtmetrixData.log.pages) {
                        var page = data.resources.gtmetrixData.log.pages[x];
                        $scope.pageLoadTime = page.pageTimings._fullyLoaded;
                        break;
                    }
                    $scope.pageRequests = data.resources.gtmetrixData.log.entries.length;
                    $scope.pageBytes = data.pageBytes;
                    var ratingId = Math.floor((100 - (data.pagespeedScore + .5)) / 10);
                    if (ratingId < ratings.length) {
                        $scope.pagespeedRating = ratings[ratingId];
                    }

                    $scope.desktopScreenshot = data.screenshot;
                    $('.load-container.desktop').addClass('loaded').css('background-image', 'url(' + $scope.desktopScreenshot + ')');

                    // Sort out
                    data = data.resources.pagespeedData;
                    $scope.pagespeedResults = [];
                    for (var x in data.rules) {
                        var found = false;

                        if (!resultDefinitions[data.rules[x].shortName]) {
                            data.rules[x].type = resultDefinitions.NA.type;
                            data.rules[x].priority = resultDefinitions.NA.priority;
                            console.log(data.rules[x]);
                        } else {
                            data.rules[x].type = resultDefinitions[data.rules[x].shortName].type;
                            data.rules[x].priority = resultDefinitions[data.rules[x].shortName].priority;
                        }

                        for (var key in $scope.pagespeedResults) {

                            if (data.rules[x].impact > $scope.pagespeedResults[key].impact ||
                                (data.rules[x].impact == $scope.pagespeedResults[key].impact && data.rules[x].score < $scope.pagespeedResults[key].score)) {
                                $scope.pagespeedResults.splice(key, 0, data.rules[x]);
                                found = true;
                                break;
                            }
                        }
                        if (!found) {
                            $scope.pagespeedResults.push(data.rules[x]);
                        }
                    }

                    $scope.isDesktopTesting = false;

                    console.log($scope.pagespeedResults);

                } else if (data.type == 'mobile') {
                    $scope.pagespeedMobileScore = data.ruleGroups.SPEED.score;
                    // $scope.pageMobileLoadTime = getMobileLoadTimeFromDesktop();
                    $scope.pageMobileLoadTime = "N/A";
                    $scope.mobileReportUrl = 'https://developers.google.com/speed/pagespeed/insights/?url=' + encodeURIComponent($scope.url) + '&tab=mobile';
                    // for (var metric in {'DOM_CONTENT_LOADED_EVENT_FIRED_MS': 1, 'FIRST_CONTENTFUL_PAINT_MS': 1}) {
                    //     var distributions = data.loadingExperience.metrics[metric].distributions;
                    //     $scope.pageMobileLoadTime += distributions[distributions.length - 1].min;
                    // }
                    $scope.pageMobileBytes = data.pageStats.overTheWireResponseBytes;
                    $scope.pageMobileRequests = data.pageStats.numberResources;
                    var ratingId = Math.floor((100 - (data.pagespeedMobileScore + .5)) / 10);
                    if (ratingId < ratings.length) {
                        $scope.pagespeedMobileRating = ratings[ratingId];
                    }
                    $scope.mobileScreenshot = 'data:' + data.screenshot.mime_type + ';base64,' + data.screenshot.data;
                    $('.load-container.mobile').addClass('loaded').css('background-image', 'url(' + $scope.mobileScreenshot + ')');

                    // Sort out
                    data = data.formattedResults;
                    $scope.pagespeedMobileResults = [];
                    for (var x in data.ruleResults) {
                        var found = false;

                        // if (!resultDefinitions[data.rules[x].shortName]) {
                        //     data.rules[x].type = resultDefinitions.NA.type;
                        //     data.rules[x].priority = resultDefinitions.NA.priority;
                        //     console.log(data.rules[x]);
                        // } else {
                        //     data.rules[x].type = resultDefinitions[data.rules[x].shortName].type;
                        //     data.rules[x].priority = resultDefinitions[data.rules[x].shortName].priority;
                        // }

                        for (var key in $scope.pagespeedMobileResults) {
                            if (data.ruleResults[x].ruleImpact > $scope.pagespeedMobileResults[key].ruleImpact ||
                                (data.ruleResults[x].ruleImpact == $scope.pagespeedMobileResults[key].ruleImpact &&
                                (100 - data.ruleResults[x].ruleImpact) < (100 - $scope.pagespeedMobileResults[key].ruleImpact))) {
                                data.ruleResults[x].score = 100 - data.ruleResults[x].ruleImpact;
                                $scope.pagespeedMobileResults.splice(key, 0, data.ruleResults[x]);
                                found = true;
                                break;
                            }
                        }
                        if (!found) {
                            data.ruleResults[x].score = 100 - data.ruleResults[x].ruleImpact;
                            $scope.pagespeedMobileResults.push(data.ruleResults[x]);
                        }
                    }

                    $scope.isMobileTesting = false;

                    console.log($scope.pagespeedMobileResults);
                }

                if (!$scope.isMobileTesting && !$scope.isDesktopTesting) {
                    $scope.testStatus = 'Completed';
                    window.animateAnalyze = false;
                    $timeout(function () {
                        $scope.nextAnalysisPage = true;
                        $scope.$apply();
                    }, 3000);
                }

            } else if (data.state == 'error') {
                console.log(data);

                $scope.testStatus = 'Error';
                window.animateAnalyze = false;
                displayErrorPrompt('Something went wrong: ' + data.error);


            } else {
                $scope.testStatus = data.state.replace(/^\w/, function (letter) {
                    return letter.toUpperCase();
                })
            }

            $scope.$apply();
        });
    }
}]);