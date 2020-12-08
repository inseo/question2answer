/*
	File:           js/etalab-core.js
	Version:        Etalab 0.1
	Description:    JavaScript helpers for Etalab theme (forked from Snow theme)

	This program is free software: you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation, either version 3 of the License, or
	(at your option) any later version.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
	GNU General Public License for more details.
*/
$(document).ready(function () {

	/**
	 * Account menu box toggle script
	 */
	$('#qam-account-toggle').click(function (e) {
		e.stopPropagation();
		closeMenuToggle();
		if ($(this).attr("aria-expanded") == "false")
			$(this).attr("aria-expanded", "true");
		else
			$(this).attr("aria-expanded", "false");
		$(this).toggleClass('account-active');
		$('.qam-account-items').slideToggle(100);
	});

	/**
	 * Main navigation toggle script
	 */
	$('.qam-menu-toggle').click(function (e) {
		e.stopPropagation();
		closeAccountToggle();

		$('.qa-nav-main').slideToggle(100);
		$(this).toggleClass('current');
		if ($(this).attr("aria-expanded") == "false")
			$(this).attr("aria-expanded", "true");
		else
			$(this).attr("aria-expanded", "false");
	});

	/**
	 * Close menu(s) when user click anywhere else
	 */
	$(document).click(function () {
		closeMenuToggle();
		closeAccountToggle();
	});

	function closeAccountToggle() {
		$('#qam-account-toggle.account-active').attr("aria-expanded", "false");
		$('#qam-account-toggle.account-active').removeClass('account-active');
		$('.qam-account-items:visible').slideUp(100);
	}

	function closeMenuToggle() {
		if($('.qam-menu-toggle').is(':visible')) {
			$('.qam-menu-toggle.current').attr("aria-expanded", "false");
			$('.qam-menu-toggle.current').removeClass('current');
			$('.qa-nav-main:visible').slideUp(100);
		}
	}

	$('.qam-account-items').click(function (event) {
		event.stopPropagation();
	});

	$('.qa-nav-main').click(function (event) {
		event.stopPropagation();
	});
});
