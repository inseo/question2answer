<?php
/*
	Etalab Theme for Question2Answer Package
	Forked from Snow Theme <http://www.q2amarket.com>

	File:           qa-theme.php
	Version:        Etalab 0.1
	Description:    Q2A theme class

	This program is free software: you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation, either version 3 of the License, or
	(at your option) any later version.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
	GNU General Public License for more details.
*/

/**
 * Etalab theme HTML customizations
 *
 * @author Etalab <https://www.etalab.gouv.fr/>
 * @license http://www.gnu.org/copyleft/gpl.html
 *
 * Snow theme HTML customizations
 *
 * @author Q2A Market <http://www.q2amarket.com>
 * @copyright (c) 2014, Q2A Market
 * @license http://www.gnu.org/copyleft/gpl.html
 */

// translations
define('Q_THEME_DIR', dirname( __FILE__ ));
qa_register_phrases(Q_THEME_DIR . '/language/cs-lang-*.php', 'etalab');

class qa_html_theme extends qa_html_theme_base
{
	protected $theme = 'etalab';

	// theme subdirectories
	private $js_dir = 'js';
	private $icon_url = 'images/icons';

	private $fixed_topbar = false;
	private $welcome_widget_class = 'wet-asphalt';

	/**
	 * Adding aditional meta for responsive design
	 */
	public function head_metas()
	{
		$this->output('<meta name="viewport" content="width=device-width, initial-scale=1"/>');
		parent::head_metas();
	}

	/**
	 * Adding theme stylesheets USELESS TO DELETE
	 */
	// public function head_css()
	// {
	// 	// UNUSED TO DELETE
	// 	if ($this->isRTL)
	// 		$this->content['css_src'][] = $this->rooturl . 'qa-styles-rtl.css?' . QA_VERSION;
	// 	parent::head_css();
	// }

	/**
	 * Adding theme javascripts
	 */
	public function head_script()
	{
		$jsUrl = $this->rooturl . $this->js_dir . '/etalab-core.js?' . QA_VERSION;
		$this->content['script'][] = '<script src="' . $jsUrl . '"></script>';

		parent::head_script();
	}

	/**
	 * Adding body class dynamically. Override needed to add class on admin/approve-users page
	 */
	public function body_tags()
	{
		$class = 'qa-template-' . qa_html($this->template);
		$class .= empty($this->theme) ? '' : ' qa-theme-' . qa_html($this->theme);

		if (isset($this->content['categoryids'])) {
			foreach ($this->content['categoryids'] as $categoryid) {
				$class .= ' qa-category-' . qa_html($categoryid);
			}
		}

		// add class if admin/approve-users page
		if ($this->template === 'admin' && qa_request_part(1) === 'approve')
			$class .= ' qam-approve-users';

		if ($this->fixed_topbar)
			$class .= ' qam-body-fixed';

		$this->output('class="' . $class . ' qa-body-js-off"');
	}

	/**
	 * Login form for user dropdown menu.
	 */
	public function nav_user_search()
	{
		$this->qam_user_account();

		$this->output('<div id="qam-account-items" class="qam-account-items">');
		if (!qa_is_logged_in()) {
			if (isset($this->content['navigation']['user']['login']) && !QA_FINAL_EXTERNAL_USERS) {
				$login = $this->content['navigation']['user']['login'];
				$emailOnly = qa_opt('allow_login_email_only');
				$inputType = $emailOnly ? 'email' : 'text';
				$this->output(
					'<form class="qam-account-login" action="' . $login['url'] . '" method="post">',
					'<p class="qam-info">' . qa_lang_html('etalab/all_fields_are_required') . '</p>',
					'<label for="qam-emailhandle">' . trim(qa_lang_html($emailOnly ? 'users/email_label' : 'users/email_handle_label'), ':') . '</label>',
					'<input type="' . $inputType . '" autocomplete="username" name="emailhandle" id="qam-emailhandle" aria-required="true" dir="auto" />',
					'<label for="qam-password">' . trim(qa_lang_html('users/password_label'), ':') . '</label>',
					'<input type="password" autocomplete="current-password" name="password" id="qam-password" aria-required="true" dir="auto" />',
					'<div class="qam-checkbox"><input type="checkbox" name="remember" id="qam-rememberme" value="1"/>',
					'<label for="qam-rememberme">' . qa_lang_html('users/remember_label') . '</label></div>',
					'<input type="hidden" name="code" value="' . qa_html(qa_get_form_security_code('login')) . '"/>',
					'<input type="submit" value="' . $login['label'] . '" class="qam-button" name="dologin"/>',
					'</form>'
				);
				$register = $this->content['navigation']['user']['register'];
				$this->output('<div class="qa-nav-user-register">');
				$this->output('<a class="qa-nav-user-link" href="'. $register['url'] .'">'. $register['label'] .'</a>');
				$this->output('</div>');

				// remove regular navigation link to signup/signin page
				unset($this->content['navigation']['user']['login']);
				unset($this->content['navigation']['user']['register']);
			}
		}
		$this->nav('user');
		$this->output('</div> <!-- END qam-account-items -->');
	}

	/**
	 * Modify markup for topbar.
	 */
	public function nav_main_sub()
	{
		$this->output('<div class="qam-skip">');
		$this->output('<p class="qam-skip-wrapper"><a href="#main" class="qam-skip-link">'. qa_lang_html('etalab/go_to_content') .'</a></p>');
		$this->output('</div> <!-- .qam-skip -->');
		$this->output('<div class="qam-topbar-wrapper">');
		$this->output('<div class="qam-topbar-body">');
		$this->output('<div class="qam-topbar-logo">');
		if(qa_opt('logo_show')) {
			$this->logo();
		} else {
			$this->output('<span class="qam-topbar-logo-link">');
			$this->output('<span class="qam-topbar-logo-title">République <br />française</span>');
			$this->output('</span>');
		}
		$this->output('</div> <!-- .qam-topbar-logo -->');
		$this->output('<div class="qam-topbar-service">');
		$this->output('<a class="qam-topbar-service-link" href="' . qa_path_html('') . '" aria-label="'. qa_html(qa_opt('site_title')) .', retour à l\'accueil">'. qa_html(qa_opt('site_title')) .'</a>');
		$this->output('<p class="qam-topbar-service-tagline">'. qa_lang_html('etalab/baseline') .'</p>');
		$this->output('</div> <!-- .qam-topbar-service -->');
		$this->qam_search();
		$this->output('<nav class="qam-topbar-nav" aria-label="'. qa_lang_html('etalab/main_navigation') .'" role="navigation">');
		$this->output('<button class="qam-menu-toggle" aria-expanded="false" aria-controls="qa-nav-main"><img role="img" width="24" height="24" src="qa-theme/Etalab/images/icon.svg#menu-toggle" alt="'. qa_lang_html('etalab/menu') .'" /></button>');
		$this->nav('main');
		$this->output('</ul>');
		$this->nav_user_search();
		$this->output('</nav>');
		$this->output('</div> <!-- .qam-topbar-body -->');
		$this->output('</div> <!-- .qam-topbar-wrapper -->');
		$this->nav('sub');
	}

	public function nav($navtype, $level = null)
	{
		$navigation = @$this->content['navigation'][$navtype];

		if ($navtype == 'user' || isset($navigation)) {
			if ($navtype == 'sub') {
				$label = "";
				foreach ($this->content['navigation']['main'] as $data) {
					if ($data['selected'] == 1) {
						$label = $data['label'];
					}
				}
				$this->output('<nav role="navigation" aria-label="' . $label . '" class="qa-nav-' . $navtype . '" id="qa-nav-' . $navtype . '">');
			} else {
				if ($navtype == 'main') {
					$this->output('<div class="qa-nav-' . $navtype . '" id="qa-nav-' . $navtype . '" hidden="hidden">');
				}
				else {
					$this->output('<div class="qa-nav-' . $navtype . '" id="qa-nav-' . $navtype . '">');
				}
			}

			// reverse order of 'opposite' items since they float right
			foreach (array_reverse($navigation, true) as $key => $navlink) {
				if (@$navlink['opposite']) {
					unset($navigation[$key]);
					$navigation[$key] = $navlink;
				}
			}

			$this->set_context('nav_type', $navtype);
			$this->nav_list($navigation, 'nav-' . $navtype, $level);
			$this->nav_clear($navtype);
			$this->clear_context('nav_type');

			if ($navtype == 'sub')
				$this->output('</nav>');
			else
				$this->output('</div>');
		}
	}

	public function nav_list($navigation, $class, $level = null)
	{
		$this->output('<ul class="qa-' . $class . '-list' . (isset($level) ? (' qa-' . $class . '-list-' . $level) : '') . '">');

		$index = 0;
		if($class == 'nav-user') {
			if (qa_is_logged_in()) {
				$userpoints = qa_get_logged_in_points();
				$pointshtml = $userpoints == 1
					? qa_lang_html_sub('main/1_point', '1', '1')
					: qa_html(qa_format_number($userpoints));
				$this->output('<li class="qa-nav-user-item qa-nav-logged-in-points">');
				$this->output(@$this->content['loggedin']['prefix'] . ' ' . @$this->content['loggedin']['data']);
				$this->output('('. $pointshtml . ' '. qa_lang_html_sub_split('main/x_points', 0)['suffix'] .')');
				$this->output('</li>');
			}
		}
		foreach ($navigation as $key => $navlink) {
			$this->set_context('nav_key', $key);
			$this->set_context('nav_index', $index++);
			$this->nav_item($key, $navlink, $class, $level);
		}

		$this->clear_context('nav_key');
		$this->clear_context('nav_index');

		$this->output('</ul>');
	}

	/**
	 * Remove the '-' from the note for the category page (notes) and add aria-current
	 * @param array $navlink
	 * @param string $class
	 */
	public function nav_link($navlink, $class)
	{
		if (isset($navlink['note']) && !empty($navlink['note'])) {
			$search = array(' - <', '> - ');
			$replace = array(' <', '> ');
			$navlink['note'] = str_replace($search, $replace, $navlink['note']);
		}
		if (isset($navlink['url'])) {
			$this->output(
				'<a href="' . $navlink['url'] . '" class="qa-' . $class . '-link' .
					(@$navlink['favorited'] ? (' qa-' . $class . '-favorited') : '') .
					'"' . (strlen(@$navlink['popup']) ? (' title="' . $navlink['popup'] . '"') : '') .
					(@$navlink['selected'] ? (' aria-current="true"') : '') .
					(isset($navlink['target']) ? (' target="' . $navlink['target'] . '"') : '') . '>' . $navlink['label'] .
					'</a>'
			);
		} else {
			$this->output(
				'<span class="qa-' . $class . '-nolink' . (@$navlink['selected'] ? (' qa-' . $class . '-selected') : '') .
					(@$navlink['favorited'] ? (' qa-' . $class . '-favorited') : '') . '"' .
					(strlen(@$navlink['popup']) ? (' title="' . $navlink['popup'] . '"') : '') .
					'>' . $navlink['label'] . '</span>'
			);
		}

		if (strlen(@$navlink['note']))
			$this->output('<span class="qa-' . $class . '-note">' . $navlink['note'] . '</span>');
	}

	/**
	 * Rearranges the layout:
	 * - Swaps the <tt>main()</tt> and <tt>sidepanel()</tt> functions
	 * - Moves the header and footer functions outside qam-body
	 * - Keeps top/high and low/bottom widgets separated
	 */
	public function body_content()
	{
		$this->body_prefix();
		$this->notices();

		$this->widgets('full', 'top');
		$this->header();

		$extratags = isset($this->content['wrapper_tags']) ? $this->content['wrapper_tags'] : '';
		$this->output('<main role="main" class="qam-body"' . $extratags . '>', '');
		$this->output('<a id="main"></a> <!-- skiplink destination --> ');
		$this->widgets('full', 'high');

		$this->output('<div class="qa-main-wrapper">', '');
		$this->main();
		$this->sidepanel();
		$this->output('</div> <!-- END main-wrapper -->');

		$this->widgets('full', 'low');
		$this->output('</main> <!-- END body-wrapper -->');

		$this->footer();

		$this->body_suffix();
	}

	/**
	 * Header in full width top bar
	 */
	public function header()
	{
		$class = $this->fixed_topbar ? ' fixed' : '';

		$this->output('<header role="banner" class="qam-topbar' . $class . '">');
		$this->nav_main_sub();
		$this->output('</header> <!-- END .qam-topbar -->');

		$this->output('<aside class="qam-ask-search-box">');
		$this->output($this->ask_button());
		$this->output('</aside>');
	}

	/**
	 * Footer in full width bottom bar
	 */
	public function footer()
	{
		$this->output('<footer class="qam-footer-box" role="contentinfo">');

		$this->output('<div class="qam-footer-row">');
		$this->widgets('full', 'bottom');
		$this->output('</div> <!-- END qam-footer-row -->');

		parent::footer();
		$this->output('</footer> <!-- END qam-footer-box -->');
	}

	public function search_field($search)
	{
		$this->output('<input type="text" placeholder="' . $search['button_label'] . '" aria-label="' . $search['button_label'] . '" ' . $search['field_tags'] . ' value="' . @$search['value'] . '" class="qa-search-field"/>');
	}

	public function search_button($search)
	{
		$this->output('<button type="submit" class="qa-search-button"><span class="u-visually-hidden">' . $search['button_label'] .'</span></button>');
	}

	/**
	 * Overridden to customize layout and styling
	 */
	public function sidepanel()
	{
		// remove sidebar for user profile pages
		if ($this->template == 'user')
			return;

		$this->output('<div class="qa-sidepanel" id="qam-sidepanel-mobile">');
		$this->widgets('side', 'top');
		$this->sidebar();
		$this->widgets('side', 'high');
		$this->widgets('side', 'low');
		if (isset($this->content['sidepanel']))
			$this->output_raw($this->content['sidepanel']);
		$this->feed();
		$this->widgets('side', 'bottom');
		$this->output('</div> <!-- qa-sidepanel -->', '');
	}

	/**
	 * Allow alternate sidebar color.
	 */
	public function sidebar()
	{
		if (isset($this->content['sidebar'])) {
			$sidebar = $this->content['sidebar'];
			if (!empty($sidebar)) {
				$this->output('<div class="qa-sidebar ' . $this->welcome_widget_class . '">');
				$this->output_raw($sidebar);
				$this->output('</div> <!-- qa-sidebar -->', '');
			}
		}
	}

	/**
	 * Override list item
	 */
	public function q_list_item($q_item)
	{
		$id = $this->getIdFromField($q_item);
		$this->output('<article ');
		if ($id !== null)
			$this->output('aria-labelledby="heading_'. $id .'" ');
		$this->output('class="qa-q-list-item' . rtrim(' ' . @$q_item['classes']) . '" ' . @$q_item['tags'] . '>');

		$this->q_item_title($q_item);
		$this->q_item_stats($q_item);
		$this->q_item_main($q_item);
		$this->q_item_clear();

		$this->output('</article> <!-- END qa-q-list-item -->', '');
	}

	public function q_item_main($q_item)
	{
		$this->output('<div class="qa-q-item-main">');

		$this->view_count($q_item);
		$this->q_item_content($q_item);
		$this->post_avatar_meta($q_item, 'qa-q-item');
		$this->post_tags($q_item, 'qa-q-item');
		$this->q_item_buttons($q_item);

    $this->output('</div>');
    $this->a_count($q_item);
	}

	public function q_item_title($q_item)
	{
		$closedText = "(" . qa_lang('main/closed') . ")";
		$imgHtml = empty($q_item['closed'])
			? ''
      : '<img src="qa-theme/Etalab/images/icon.svg#q-list-close-icon" class="qam-q-list-close-icon" alt="' . $closedText . '" width="20" height="20" />';

		$id = $this->getIdFromField($q_item);
		$this->output('<h2 class="qa-q-item-title" ');
		if ($id !== null)
			$this->output('id="heading_'. $id .'" ');
		$this->output('>');
		$this->output(	'<a href="' . $q_item['url'] . '">',
		$q_item['title'],
		$imgHtml,
		'</a>',
		'</h2>'
		);
	}

	public function vote_buttons($post)
	{
		switch (@$post['vote_state']) {
      case 'voted_up':
        $this->output('<span class="qam-vote-first-button qam-voted">');
        $this->post_hover_button($post, 'vote_up_tags', '+', 'qa-vote');
        $this->output('</span>');
				break;

      case 'voted_up_disabled':
        $this->output('<span class="qam-vote-first-button qam-vote-up-disabled">');
        $this->post_disabled_button($post, 'vote_up_tags', '+', 'qa-vote-button');
        $this->output('</span>');
				break;

      case 'voted_down':
        $this->output('<span class="qam-vote-first-button qam-voted">');
        $this->post_hover_button($post, 'vote_down_tags', '&ndash;', 'qa-vote');
        $this->output('</span>');
				break;

      case 'voted_down_disabled':
        $this->output('<span class="qam-vote-first-button qam-vote-down-disabled">');
        $this->post_disabled_button($post, 'vote_down_tags', '&ndash;', 'qa-vote-button');
        $this->output('</span>');
        break;

      case 'up_only':
        $this->output('<span class="qam-vote-first-button qam-vote-up">');
        $this->post_hover_button($post, 'vote_up_tags', '+', 'qa-vote');
        $this->output('</span>');
        $this->output('<span class="qam-vote-second-button qam-vote-down-disabled">');
        $this->post_disabled_button($post, 'vote_down_tags', '&ndash;', 'qa-vote-button');
        $this->output('</span>');
				break;

      case 'enabled':
        $this->output('<span class="qam-vote-first-button qam-vote-up">');
        $this->post_hover_button($post, 'vote_up_tags', '+', 'qa-vote');
        $this->output('</span>');
        $this->output('<span class="qam-vote-second-button qam-vote-down">');
        $this->post_hover_button($post, 'vote_down_tags', '&ndash;', 'qa-vote');
        $this->output('</span>');
				break;

      default:
        $this->output('<span class="qam-vote-first-button qam-vote-up-disabled">');
        $this->post_disabled_button($post, 'vote_up_tags', '+', 'qa-vote-button');
        $this->output('</span>');
        $this->output('<span class="qam-vote-second-button qam-vote-down-disabled">');
        $this->post_disabled_button($post, 'vote_down_tags', '&ndash;', 'qa-vote-button');
        $this->output('</span>');
				break;
		}
	}

	public function vote_count($post)
	{
		// Hide vote when 0
		if ($post['raw']['basetype'] === 'C' && $post['raw']['netvotes'] == 0) {
			$post['netvotes_view']['data'] = '';
		}

		// You can also use $post['upvotes_raw'], $post['downvotes_raw'], $post['netvotes_raw'] to get
		// raw integer vote counts, for graphing or showing in other non-textual ways

		$this->output('<p aria-live="polite" class="qa-vote-count ' . (($post['vote_view'] == 'updown') ? 'qa-vote-count-updown' : 'qa-vote-count-net') . '"' . @$post['vote_count_tags'] . '>');

		if ($post['vote_view'] == 'updown') {
			$this->output_split($post['upvotes_view'], 'qa-upvote-count');
			$this->output_split($post['downvotes_view'], 'qa-downvote-count');
		} else {
			$this->output_split($post['netvotes_view'], 'qa-netvote-count');
		}

		$this->output('</p>');
	}

	public function a_count($post)
	{
		// You can also use $post['answers_raw'] to get a raw integer count of answers
		$this->output_split(@$post['answers'], 'qa-a-count', 'p', 'span',
			@$post['answer_selected'] ? 'qa-a-count-selected' : (@$post['answers_raw'] ? null : 'qa-a-count-zero'));
	}

	public function a_selection($post)
	{
		$this->output('<div class="qa-a-selection">');

		if (isset($post['select_text']))
			$this->output('<p class="qa-a-selected-text">' . @$post['select_text'] . '</p>');
	
		if (isset($post['select_tags']))
			$this->post_hover_button($post, 'select_tags', qa_lang('question/select_popup'), 'qa-a-select');
		elseif (isset($post['unselect_tags']))
			$this->post_hover_button($post, 'unselect_tags', qa_lang('question/unselect_popup'), 'qa-a-unselect');
		elseif ($post['selected'])
			$this->output('<div class="qa-a-selected">&nbsp;</div>');

		$this->output('</div>');
	}

	public function post_hover_button($post, $element, $value, $class)
	{
		if (isset($post[$element]))
			$this->output('<input ' . $post[$element] . ' type="submit" value="' . $value . '" class="' . $class . '-button"/> ');
	}

	public function post_disabled_button($post, $element, $value, $class)
	{
		if (isset($post[$element]))
			$this->output('<input ' . $post[$element] . ' type="submit" value="' . $value . '" class="' . $class . '-disabled" disabled="disabled"/> ');
	}

	public function post_avatar_meta($post, $class, $avatarprefix = null, $metaprefix = null, $metaseparator = '<br/>')
	{
		$this->output('<p class="' . $class . '-avatar-meta">');
		$this->avatar($post, $class, $avatarprefix);
		$this->post_meta($post, $class, $metaprefix, $metaseparator);
		$this->output('</p>');
	}

	public function post_tag_item($taghtml, $class)
	{
		if(stristr($taghtml, 'qa-tag-favorited')) {
			$favhtml = ' <span class="u-visually-hidden">('. qa_lang_html('etalab/favorited') .')</span>';
			$taghtml = str_replace ('</a>', $favhtml.'</a>', $taghtml);
		}

		$this->output('<li class="' . $class . '-tag-item">' . $taghtml . '</li>');
	}

	public function avatar($item, $class, $prefix = null)
	{
		if (isset($item['avatar'])) {
			if (isset($prefix))
				$this->output($prefix);
			$item['avatar'] = str_replace('alt=""', 'alt="'. $item['raw']['handle'] .' ('. qa_lang_html('etalab/open_profil') .')"', $item['avatar']);

			$this->output(
				'<span class="' . $class . '-avatar">',
				$item['avatar'],
				'</span>'
			);
		}
	}

	/**
	 * Add RSS feeds icon
	 */
	public function favorite()
	{
		parent::favorite();

		// RSS feed link in title
		if (isset($this->content['feed']['url'])) {
			$feed = $this->content['feed'];
			$label = isset($feed['label']) ? $feed['label'] : '';
			$this->output(
				'<a class="qam-rss" href="' . $feed['url'] . '">',
        		'<img role="img" src="qa-theme/Etalab/images/icon.svg#title-rss" alt="' . qa_lang_html('admin/feeds_title') . '&nbsp;: ' . $label . '" width="24" height="24" />',
				'</a>'
			);
		}
	}

	public function favorite_button($tags, $class)
	{
		if (isset($tags)) {
			preg_match('~title="(.*?)"~', $tags, $output);
			isset($output[1]) ? $value = $output[1] : $value = null;
			$this->output('<input ' . $tags . ' type="submit" value="'. $value .'" class="' . $class . '-button"/> ');
		}
	}

	/**
	 * Add closed icon for closed questions
	 */
	public function title()
	{
		$q_view = isset($this->content['q_view']) ? $this->content['q_view'] : null;

		// link title where appropriate
		$url = isset($q_view['url']) ? $q_view['url'] : false;

		// add closed image
		$closedText = qa_lang('main/closed');
		$imgHtml = empty($q_view['closed'])
			? ''
			: '<img src="' . $this->rooturl . $this->icon_url . '/closed-q-view.png" class="qam-q-view-close-icon" alt="' . $closedText . '" width="24" height="24" title="' . $closedText . '"/>';

		if (isset($this->content['title'])) {
			$this->output(
				$imgHtml,
				$url ? '<a href="' . $url . '">' : '',
				$this->content['title'],
				$url ? '</a>' : ''
			);
		}
	}

	/**
	 * Add view counter to question list
	 * @param array $q_item
	 */
	public function q_item_stats($q_item)
	{
		$this->output('<div class="qa-q-item-stats">');
		// TODO
		//if($q_item['netvotes_view']['data'] == 0) {
		//	if(substr($q_item['netvotes_view']['suffix'], -1) == "s") {
		//		$q_item['netvotes_view']['suffix'] = substr($q_item['netvotes_view']['suffix'],0,-1);
		//	}
		//} 
		$this->voting($q_item);
		parent::view_count($q_item);

		$this->output('</div>');
	}

	/**
	 * Prevent display view counter on usual place
	 * @param array $q_item
	 */
	public function view_count($q_item)
	{
		// do nothing
	}

	public function q_view_stats($q_view)
	{
		$this->output('<div class="qa-q-view-stats">');
		// TODO
		// if($q_view['netvotes_view']['data'] == 0) {
		// 	if(substr(strip_tags($q_view['netvotes_view']['suffix']), -1) == "s") {
		// 		// TODO
		// 		$q_view['netvotes_view']['suffix'] = substr($q_view['netvotes_view']['suffix'],0,-1);
		// 	}
		// } 
		$this->voting($q_view);
		$this->a_count($q_view);
		parent::view_count($q_view);
		$this->output('</div>');
	}

	public function q_view_closed($q_view)
	{
		if (!empty($q_view['closed'])) {
			$haslink = isset($q_view['closed']['url']);

			$this->output(
				'<p class="qa-q-view-closed">',
				$q_view['closed']['label'],
				($haslink ? ('<a href="' . $q_view['closed']['url'] . '"') : '<span') . ' class="qa-q-view-closed-content">',
				$q_view['closed']['content'],
				$haslink ? '</a>' : '</span>',
				'</p>'
			);
		}
	}

	public function q_view_follows($q_view)
	{
		if (!empty($q_view['follows']))
			$this->output(
				'<p class="qa-q-view-follows">',
				$q_view['follows']['label'],
				'<a href="' . $q_view['follows']['url'] . '" class="qa-q-view-follows-link">' . $q_view['follows']['title'] . '</a>',
				'</p>'
			);
	}

	/**
	 * Modify user whometa, move to top
	 * @param array $q_view
	 */
	public function q_view_main($q_view)
	{
		$this->output('<div class="qa-q-view-main">');

		if (isset($q_view['main_form_tags'])) {
			$this->output('<form ' . $q_view['main_form_tags'] . '>'); // form for buttons on question
		}

		$this->post_avatar_meta($q_view, 'qa-q-view');
		$this->q_view_content($q_view);
		$this->q_view_extra($q_view);
		$this->q_view_follows($q_view);
		$this->q_view_closed($q_view);
		$this->post_tags($q_view, 'qa-q-view');

		$this->q_view_buttons($q_view);

		if (isset($q_view['main_form_tags'])) {
			if (isset($q_view['buttons_form_hidden']))
				$this->form_hidden_elements($q_view['buttons_form_hidden']);
			$this->output('</form>');
		}

		$this->c_list(isset($q_view['c_list']) ? $q_view['c_list'] : null, 'qa-q-view');
		$this->c_form(isset($q_view['c_form']) ? $q_view['c_form'] : null);

		$this->output('</div> <!-- END qa-q-view-main -->');
	}

	/**
	 * Move user whometa to top in answer
	 * @param array $a_item
	 */
	public function a_item_main($a_item)
	{
		$this->output('<div class="qa-a-item-main">');

		if (isset($a_item['main_form_tags'])) {
			$this->output('<form ' . $a_item['main_form_tags'] . '>'); // form for buttons on answer
		}

		$this->post_avatar_meta($a_item, 'qa-a-item');

		if ($a_item['hidden'])
			$this->output('<div class="qa-a-item-hidden">');
		elseif ($a_item['selected'])
			$this->output('<div class="qa-a-item-selected">');

		$this->a_selection($a_item);
		if (isset($a_item['error']))
			$this->error($a_item['error']);
		$this->a_item_content($a_item);

		if ($a_item['hidden'] || $a_item['selected'])
			$this->output('</div>');

		$this->a_item_buttons($a_item);

		if (isset($a_item['main_form_tags'])) {
			if (isset($a_item['buttons_form_hidden']))
				$this->form_hidden_elements($a_item['buttons_form_hidden']);
			$this->output('</form>');
		}

		$this->c_list(isset($a_item['c_list']) ? $a_item['c_list'] : null, 'qa-a-item');
		$this->c_form(isset($a_item['c_form']) ? $a_item['c_form'] : null);

		$this->output('</div> <!-- END qa-a-item-main -->');
	}

	/**
	 * Remove comment voting here
	 * @param array $c_item
	 */
	public function c_list_item($c_item)
	{
		$extraclass = @$c_item['classes'] . (@$c_item['hidden'] ? ' qa-c-item-hidden' : '');

		$this->output('<div class="qa-c-list-item ' . $extraclass . '" ' . @$c_item['tags'] . '>');

		$this->c_item_main($c_item);
		$this->c_item_clear();

		$this->output('</div> <!-- END qa-c-item -->');
	}

	/**
	 * Move user whometa to top in comment, add comment voting back in
	 * @param array $c_item
	 */
	public function c_item_main($c_item)
	{
		$this->post_avatar_meta($c_item, 'qa-c-item');

		if (isset($c_item['error']))
			$this->error($c_item['error']);

		if (isset($c_item['main_form_tags'])) {
			$this->output('<form ' . $c_item['main_form_tags'] . '>'); // form for comment voting buttons
		}

		$this->voting($c_item);

		if (isset($c_item['main_form_tags'])) {
			$this->form_hidden_elements(@$c_item['voting_form_hidden']);
			$this->output('</form>');
		}

		if (isset($c_item['main_form_tags'])) {
			$this->output('<form ' . $c_item['main_form_tags'] . '>'); // form for buttons on comment
		}

		if (isset($c_item['expand_tags']))
			$this->c_item_expand($c_item);
		elseif (isset($c_item['url']))
			$this->c_item_link($c_item);
		else
			$this->c_item_content($c_item);

		$this->output('<div class="qa-c-item-footer">');
		$this->c_item_buttons($c_item);
		$this->output('</div>');

		if (isset($c_item['main_form_tags'])) {
			$this->form_hidden_elements(@$c_item['buttons_form_hidden']);
			$this->output('</form>');
		}
	}

	/**
	 * Q2A Market attribution.
	 * I'd really appreciate you displaying this link on your Q2A site. Thank you - Jatin
	 */
	public function attribution()
	{
		// floated right
		$this->output(
			'<ul class="qa-attribution-footer">',
			'<li class="qa-attribution-footer-item">'. qa_lang_html('etalab/powered_by') .' <a class="qa-attribution-footer-link" href="http://www.question2answer.org/">Question2Answer</a></li>',
			'<li class="qa-attribution-footer-item">'. qa_lang_html('etalab/theme_by') .' <a class="qa-attribution-footer-link" href="https://www.etalab.gouv.fr/">Etalab</a></li>',
			'</ul>'
		);
	}

	// Remove empty div
	public function footer_clear()
	{
	}

	/**
	 * User account navigation item. This will return based on login information.
	 * If user is logged in, it will populate and account links.
	 * If user is guest, it will populate login form and registration link.
	 */
	private function qam_user_account()
	{
		if (qa_is_logged_in()) {
			$handle = qa_lang_html_sub_split('main/logged_in_x', 0)['prefix'];
			$toggleClass = 'qam-logged-in';
		} else {
			$handle = $this->content['navigation']['user']['login']['label'] . "/" . $this->content['navigation']['user']['register']['label'];
			$toggleClass = 'qam-logged-out';
		}

		$this->output(
			'<button aria-expanded="false" aria-controls="qam-account-items" id="qam-account-toggle" class="' . $toggleClass . '">',
			'<span class="u-visually-hidden@umd">',
			$handle,
			'<span>',
			'</button>'
		);
	}

	/**
	 * Add role="search" on search container
	 */
  // @TODO Voir s'il n'y a pas moyen d'avoir plus simple en sortie…
	private function qam_search()
	{
    $this->output('<div role="search" class="qam-search">');
    $this->output('<button class="qam-search-toggle" aria-expanded="false" aria-controls="qa-search"><img role="img" width="24" height="24" src="qa-theme/Etalab/images/icon.svg#search-toggle" alt="'. qa_lang_html('etalab/menu') .'" /></button>');
    $this->output('<div id="qam-search" hidden="hidden">');
    $this->search();
    $this->output('</div> <!-- #qam-search -->');
		$this->output('</div>');
	}

	/**
	 * Custom ask button for medium and small screen
	 * @return string Ask button html markup
	 */
	private function ask_button()
	{
		return
			'<div class="qam-ask-mobile">' .
			'<a href="' . qa_path('ask', null, qa_path_to_root()) . '">' .
			qa_lang_html('main/nav_ask') .
			'</a>' .
			'</div>';
	}

	/**
	 * Accessibility of forms and layout tables
	 */
	public function form_body($form)
	{
		if (@$form['boxed'])
			$this->output('<div class="qa-form-table-boxed">');

		$columns = $this->form_columns($form);

		if ($columns)
			$this->output('<table class="qa-form-' . $form['style'] . '-table" role="presentation">');

		$this->form_ok($form, $columns);
		$this->form_fields($form, $columns);
		$this->form_buttons($form, $columns);

		if ($columns)
			$this->output('</table>');

		$this->form_hidden($form);

		if (@$form['boxed'])
			$this->output('</div>');
	}

	public function form_ok($form, $columns)
	{
		$this->output('<tr role="status">');
		if (!empty($form['ok'])) {
			$this->output(
				'<td colspan="' . $columns . '" class="qa-form-' . $form['style'] . '-ok">',
				$form['ok'],
				'</td>'
			);
		}
		$this->output('</tr>');
	}

	public function form_field_rows($form, $columns, $field)
	{
		$style = $form['style'];

		if (isset($field['style'])) { // field has different style to most of form
			$style = $field['style'];
			$colspan = $columns;
			$columns = ($style == 'wide') ? 2 : 1;
		} else
			$colspan = null;

		$prefixed = (@$field['type'] == 'checkbox') && ($columns == 1) && !empty($field['label']);
		$suffixed = (@$field['type'] == 'select' || @$field['type'] == 'number') && $columns == 1 && !empty($field['label']) && !@$field['loose'];
		$skipdata = @$field['tight'];
		$tworows = ($columns == 1) && (!empty($field['label'])) && (!$skipdata) &&
			((!($prefixed || $suffixed)) || (!empty($field['error'])) || (!empty($field['note'])));

		if (isset($field['id'])) {
			if ($columns == 1) {
				if (isset($field['type']) && $field['type'] == "select-radio") {
					$this->output('<tbody id="' . $field['id'] . '" role="radiogroup" aria-label="' . $field['label'] . '">', '<tr>');
				} else {
					$this->output('<tbody id="' . $field['id'] . '" role="presentation">', '<tr>');
				}
			} else
				$this->output('<tr id="' . $field['id'] . '">');
		} else
			$this->output('<tr>');

		if ($columns > 1 || !empty($field['label']))
			$this->form_label($field, $style, $columns, $prefixed, $suffixed, $colspan);

		if ($tworows) {
			$this->output(
				'</tr>',
				'<tr>'
			);
		}

		if (!$skipdata)
			$this->form_data($field, $style, $columns, !($prefixed || $suffixed), $colspan);

		$this->output('</tr>');

		if ($columns == 1 && isset($field['id']))
			$this->output('</tbody>');
	}

	public function form_label($field, $style, $columns, $prefixed, $suffixed, $colspan)
	{
		$extratags = '';

		if ($columns > 1 && (@$field['type'] == 'select-radio' || @$field['rows'] > 1))
			$extratags .= ' style="vertical-align:top;"';

		if (isset($colspan))
			$extratags .= ' colspan="' . $colspan . '"';

		$this->output('<td class="qa-form-' . $style . '-label"' . $extratags . '>');

		// Add <label> when field with id or name is identified (except for select-radio)
		$id = $this->getIdFromField($field);

		if ((isset($field['type']) && $field['type'] == "select-radio") || $id == null) {
			$this->output('<p>');
		} else {
			$id !== null ? $this->output('<label for="' . $id . '">') : '';
		}

		if ($prefixed) {
			$this->form_field($field, $style);
		}

		$this->output(@$field['label']);
		if ($suffixed) {
			$this->output('&nbsp;');
			$this->form_field($field, $style);
		}

		if ((isset($field['type']) && $field['type'] == "select-radio") || $id == null) {
			$this->output('</p>');
		} else {
			if($this->isItARequiredField($field)) {
				$this->output(' <span class="required" aria-hidden="true">('. qa_lang_html('etalab/required') .')</span>');
			}
			$id !== null ? $this->output('</label>') : '';
		}
		$this->output('</td>');
	}

	/**
	 * Find any attribute value in tags string
	 * Return value of attribute or null
	 * @param $field
	 * @param $attribute (eg: 'id', 'name', etc.)
	 */
	private function extractAttributeFromTags($field, $attribute)
	{
		$value = null;
		if (isset($field['tags'])) {
			preg_match('~' . $attribute . '="(.*?)"~', $field['tags'], $output);
			isset($output[1]) ? $value = $output[1] : $value = null;
		}
		return $value;
	}

	/**
	 * Find the id of field inside the tags or create it
	 * Return id or null
	 * @param $field
	 */
	private function getIdFromField($field)
	{
		$id = $this->extractAttributeFromTags($field, 'id');
		if ($id == null)
			$id = $this->extractAttributeFromTags($field, 'name');
		return $id;
	}

	/**
	 * Find if it is a required field (based on id's)
	 * Return true/false
	 * @param $field
	 */
	private function isItARequiredField($field)
	{
		$isIt = false;
		$id = $this->getIdFromField($field);
		switch($id) {
			case "emailhandle":
			case "password":
			case "handle":
			case "email":
			case "terms":
			case "q_title":
				$isIt = true;
				break;
			default:
			break;
		}
		return $isIt;
	}

	/**
	 * Return a new $tags string to add on a field element.
	 * $endOfId is not required and used to complete the id adding a string at the end
	 * @param $field
	 * @param $endOfId
	 */
	private function adaptFieldTagsForAccessibility($field, $endOfId = null)
	{
		$id = null;
		$ariaDescribedBy = "";
		$tags = @$field['tags'];
		if ($this->extractAttributeFromTags($field, "id") !== null) {
			$id = $this->extractAttributeFromTags($field, "id");
			if ($endOfId !== null)
				$tags = str_replace('id="' . $id . '"', 'id="' . $id . $endOfId . '"', $tags);
		} else {
			if ($this->extractAttributeFromTags($field, "name") !== null) {
				$id = $this->extractAttributeFromTags($field, "name");
				if ($endOfId !== null)
					$tags .= ' id="' . $id . $endOfId . '"';
				else
					$tags .= ' id="' . $id . '"';
			}
		}
		if (isset($field['error']) && $field['error'] !== "" && $id !== null) {
			$ariaDescribedBy .= "error_" . $id . " ";
			$tags .= ' aria-invalid="true"';
		}
		if (isset($field['note']) && $id !== null)
			$ariaDescribedBy .= "note_" . $id . " ";
		if ($ariaDescribedBy !== "")
			$tags .= ' aria-describedby="' . $ariaDescribedBy . '"';
		if ($this->isItARequiredField($field))
			$tags .= ' aria-required="true"';
		return $tags;
	}

	public function form_checkbox($field, $style)
	{
		$tags = $this->adaptFieldTagsForAccessibility($field);
		$this->output('<input ' . $tags . ' type="checkbox" value="1"' . (@$field['value'] ? ' checked' : '') . ' class="qa-form-' . $style . '-checkbox"/>');
	}

	public function form_password($field, $style)
	{
		$tags = $this->adaptFieldTagsForAccessibility($field);
		$this->output('<input ' . $tags . ' type="password" value="' . @$field['value'] . '" class="qa-form-' . $style . '-text"/>');
	}

	public function form_number($field, $style)
	{
		$tags = $this->adaptFieldTagsForAccessibility($field);
		$this->output('<input ' . $tags . ' type="text" value="' . @$field['value'] . '" class="qa-form-' . $style . '-number"/>');
	}

	public function form_file($field, $style)
	{
		$tags = $this->adaptFieldTagsForAccessibility($field);
		$this->output('<input ' . $tags . ' type="file" class="qa-form-' . $style . '-file"/>');
	}

	/**
	 * Output a <select> element. The $field array may contain the following keys:
	 *   options: (required) a key-value array containing all the options in the select.
	 *   tags: any attributes to be added to the select.
	 *   value: the selected value from the 'options' parameter.
	 *   match_by: whether to match the 'value' (default) or 'key' of each option to determine if it is to be selected.
	 * @param $field
	 * @param $style
	 */
	public function form_select($field, $style)
	{
		$tags = $this->adaptFieldTagsForAccessibility($field);
		$this->output('<select ' . $tags . ' class="qa-form-' . $style . '-select">');

		// Only match by key if it is explicitly specified. Otherwise, for backwards compatibility, match by value
		$matchbykey = isset($field['match_by']) && $field['match_by'] === 'key';

		foreach ($field['options'] as $key => $value) {
			$selected = isset($field['value']) && (
				($matchbykey && $key === $field['value']) ||
				(!$matchbykey && $value === $field['value']));
			$this->output('<option value="' . $key . '"' . ($selected ? ' selected' : '') . '>' . $value . '</option>');
		}

		$this->output('</select>');
	}

	public function form_select_radio($field, $style)
	{
		$radios = 0;

		$this->output('<div role="list">');
		foreach ($field['options'] as $tag => $value) {
			$id = $this->getIdFromField($field);
			$tags = $this->adaptFieldTagsForAccessibility($field, $radios);

      $this->output('<div role="listitem" class="qam-radio">');
      $this->output('<input ' . @$tags . ' type="radio" value="' . $tag . '"' . (($value == @$field['value']) ? ' checked' : '') . ' class="qa-form-' . $style . '-radio"/> ');
			$this->output('<label for="' . $id . $radios . '">');
			$this->output($value);
			$this->output('</label>');
			$this->output('</div>');

			$radios++;
		}
		$this->output('</div>');
	}

	public function form_email($field, $style)
	{
		$tags = $this->adaptFieldTagsForAccessibility($field);
		$this->output('<input ' . $tags . ' type="email" value="' . @$field['value'] . '" class="qa-form-' . $style . '-email"/>');
	}

	public function form_text_single_row($field, $style)
	{
		$tags = $this->adaptFieldTagsForAccessibility($field);
		$this->output('<input ' . $tags . ' type="text" value="' . @$field['value'] . '" class="qa-form-' . $style . '-text"/>');
	}

	public function form_text_multi_row($field, $style)
	{
		$tags = $this->adaptFieldTagsForAccessibility($field);
		$this->output('<textarea ' . $tags . ' rows="' . (int)$field['rows'] . '" cols="40" class="qa-form-' . $style . '-text">' . @$field['value'] . '</textarea>');
	}

	public function form_error($field, $style, $columns)
	{
		$id = $this->getIdFromField($field);
		$tag = ($columns > 1) ? 'span' : 'p';
		$this->output('<' . $tag . ' role="alert" ');
		if ($id !== null)
			$this->output('id="error_' . $id . '" ');
		$this->output('class="qa-form-' . $style . '-error">' . $field['error'] . '</' . $tag . '>');
	}

	public function form_note($field, $style, $columns)
	{
		$id = $this->getIdFromField($field);
		$tag = ($columns > 1) ? 'span' : 'p';
		$this->output('<' . $tag . ' ');
		if ($id !== null)
			$this->output('id="note_' . $id . '" ');
		$this->output('class="qa-form-' . $style . '-note">' . @$field['note'] . '</' . $tag . '>');
	}

	public function ranking_table($ranking, $class)
	{
		$rows = min($ranking['rows'], count($ranking['items']));

		if ($rows > 0) {
			$this->output('<ul class="' . $class . '-table">');
			$columns = ceil(count($ranking['items']) / $rows);

			for ($row = 0; $row < $rows; $row++) {
				$this->set_context('ranking_row', $row);
				$this->output('<li>');

				for ($column = 0; $column < $columns; $column++) {
					$this->set_context('ranking_column', $column);
					$this->ranking_table_item(@$ranking['items'][$column * $rows + $row], $class, $column > 0);
				}

				$this->clear_context('ranking_column');
				$this->output('</li>');
			}
			$this->clear_context('ranking_row');
			$this->output('</ul>');
		}
	}

	public function ranking_spacer($class)
	{
		$this->output('<span class="' . $class . '-spacer">&nbsp;</span>');
	}

	public function ranking_cell($content, $class)
	{
		$this->output('<span class="' . $class . '">' . $content . '</span>');
	}

	public function page_links()
	{
		$page_links = @$this->content['page_links'];

		if (!empty($page_links)) {
			$this->output('<nav role="navigation" aria-label="'. qa_lang_html('etalab/pagination') .'" class="qa-page-links">');

			$this->page_links_label(@$page_links['label']);
			$this->page_links_list(@$page_links['items']);
			$this->page_links_clear();

			$this->output('</nav>');
		}
	}

	public function page_links_item($page_link)
	{
		if($page_link['type'] == "this") {
			$this->output('<li aria-current="true" class="qa-page-links-item">');
		} else {
			$this->output('<li class="qa-page-links-item">');
		}
		$this->page_link_content($page_link);
		$this->output('</li>');
	}
}
