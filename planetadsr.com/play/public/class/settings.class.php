<?php

class setting {
	
	private function doSettingQuery($setting) {
		$settingQry = mysql_query("SELECT * FROM yt_config WHERE config_name='".mysql_real_escape_string($setting)."'");
		return mysql_result($settingQry,0,'config_value');
	}

	public function getSource() {
		return setting::doSettingQuery('source');
	}
	
	public function getLink() {
		return setting::doSettingQuery('link');
	}
	
	public function getDisplayViews() {
		return setting::doSettingQuery('display_views');
	}
	
	public function getDisplayAuthor() {
		return setting::doSettingQuery('display_author');
	}
	
	public function getTitleChars() {
		return setting::doSettingQuery('title_chars');
	}
	
	public function getDescriptionChars() {
		return setting::doSettingQuery('description_chars');
	}
	
	public function getMeasureId() {
		return setting::doSettingQuery('measure_id');
	}
	
	public function getTimezone() {
		return setting::doSettingQuery('timezone');
	}
	
	public function getCustomWidth() {
		return setting::doSettingQuery('custom_width');
	}
	
	public function getCustomHeight() {
		return setting::doSettingQuery('custom_height');
	}
	
	public function getPlayerWidth() {
		if($this->getMeasureId() == 0) {
			return $this->getCustomWidth();
		} else {
			$dimensionQry= mysql_query("SELECT * FROM yt_measures WHERE measure_id = '".mysql_real_escape_string($this->getMeasureId())."'");
			return mysql_result($dimensionQry,0,'measure_width'); 
		}
	}
	
	public function getPlayerHeight() {
		if($this->getMeasureId() == 0) {
			return $this->getCustomHeight();
		} else {
			$dimensionQry= mysql_query("SELECT * FROM yt_measures WHERE measure_id = '".mysql_real_escape_string($this->getMeasureId())."'");
			return mysql_result($dimensionQry,0,'measure_height'); 
		}
	}
	
	public function getThumbWidth() {
		return setting::doSettingQuery('thumb_width');
	}
	
	public function getThumbHeight() {
		$height=90*$this->getThumbWidth()/120;
		return $height;
	}
	
	public function getLayout() {
		return setting::doSettingQuery('layout');
	}
	
	public function getAutoplay() {
		return setting::doSettingQuery('autoplay');
	}
	
	public function getDisplayTitle() {
		return setting::doSettingQuery('display_title');
	}
	
	public function getDisplayDescription() {
		return setting::doSettingQuery('display_description');
	}
	
	public function getDisplayThumb() {
		return setting::doSettingQuery('display_thumb');
	}
	
	public function getVideoNum() {
		return setting::doSettingQuery('video_num');
	}
	
	public function getDisplayVideolist() {
		return setting::doSettingQuery('display_videolist');
	}
	
	public function getPlaylistStart() {
		return setting::doSettingQuery('playlist_start');
	}
	
	public function getScheduleVideo() {
		return setting::doSettingQuery('schedule_video');
	}
	
	public function getManagement() {
		return setting::doSettingQuery('management');
	}
	
	public function getSourceType() {
		return setting::doSettingQuery('source_type');
	}
	
	public function getSourceSource() {
		return setting::doSettingQuery('source_source');
	}
	
	public function getSourceLink() {
		return setting::doSettingQuery('source_link');
	}
	
	public function getSourceNumVideos() {
		return setting::doSettingQuery('source_num_videos');
	}
	
	public function getLoopVideos() {
		return setting::doSettingQuery('loop_videos');
	}
	
	public function getVideolistWidth() {
		return setting::doSettingQuery('videolist_width');
	}
	
	public function getVideolistHeight() {
		return setting::doSettingQuery('videolist_height');
	}
	
	public function getVideolistMargin() {
		return setting::doSettingQuery('videolist_margin');
	}
	
	public function getVideolistPosition() {
		return setting::doSettingQuery('videolist_position');
	}
	
	public function getVideoNavigation() {
		return setting::doSettingQuery('video_navigation');
	}
	
	public function getThumbBg() {
		return setting::doSettingQuery('thumb_bg');
	}
	
	public function getThumbBgHover() {
		return setting::doSettingQuery('thumb_bg_hover');
	}
	
	public function getThumbBgSel() {
		return setting::doSettingQuery('thumb_bg_sel');
	}
	
	public function getShowVideoInfo() {
		return setting::doSettingQuery('show_video_info');
	}

	public function getButtonBackColor() {
		return setting::doSettingQuery('button_back_color');
	}
	
	public function getButtonBackColorHover() {
		return setting::doSettingQuery('button_back_color_hover');
	}
	
	public function getButtonColor() {
		return setting::doSettingQuery('button_color');
	}
	
	public function getButtonColorHover() {
		return setting::doSettingQuery('button_color_hover');
	}
	
	public function getButtonPadding() {
		return setting::doSettingQuery('button_padding');
	}
	
	public function getButtonMarginVideolist() {
		return setting::doSettingQuery('button_margin_videolist');
	}
	
	public function getButtonMarginBetween() {
		return setting::doSettingQuery('button_margin_between');
	}
	
	public function getVideoPadding() {
		return setting::doSettingQuery('video_padding');
	}
	
	public function getShowScheduleList() {
		return setting::doSettingQuery('show_schedule_list');
	}
	
	public function getButtonFontSize() {
		return setting::doSettingQuery('button_font_size');
	}
	
	public function getVideoInfoFontSize() {
		return setting::doSettingQuery('video_info_font_size');
	}
	
	public function getVideolistFontSize() {
		return setting::doSettingQuery('videolist_font_size');
	}
	
	public function getScheduleListFontSize() {
		return setting::doSettingQuery('schedule_list_font_size');
	}
	
	public function getScheduleListHeight() {
		return setting::doSettingQuery('schedule_list_height');
	}
	
	public function getScheduleListMarginTop() {
		return setting::doSettingQuery('schedule_list_margin_top');
	}
	
	public function getYtApiKey() {
		return setting::doSettingQuery('yt_api_key');
	}
}

?>
