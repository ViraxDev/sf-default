import('./theme/config.js');
import SimpleBar from 'simplebar';
window.SimpleBar = SimpleBar;

import('./theme/simplebar/simplebar.min.css');
// import('./theme/css/theme-rtl.css');
import('./theme/css/theme.css');
import('./theme/css/user-rtl.css');
import('./theme/css/user.css');

import('@popperjs/core');

import { Toast } from 'bootstrap';
window.Toast = Toast;

import AnchorJS from 'anchor-js';
window.AnchorJS = AnchorJS;

import is from 'is_js';
window.is = is;

import('./theme/vendors/fontawesome/all.min.js');
import _ from 'lodash';
window._ = _;

import('./theme/vendors/list.js/list.min.js');
import('./theme/theme.js');

var isRTL = JSON.parse(localStorage.getItem('isRTL'));
if (isRTL) {
    var linkDefault = document.getElementById('style-default');
    var userLinkDefault = document.getElementById('user-style-default');
    linkDefault.setAttribute('disabled', true);
    userLinkDefault.setAttribute('disabled', true);
    document.querySelector('html').setAttribute('dir', 'rtl');
} else {
    var linkRTL = document.getElementById('style-rtl');
    var userLinkRTL = document.getElementById('user-style-rtl');

    if (linkRTL !== null) {
        linkRTL.setAttribute('disabled', true);
    }

    if (userLinkRTL) {
        userLinkRTL.setAttribute('disabled', true);
    }
}
