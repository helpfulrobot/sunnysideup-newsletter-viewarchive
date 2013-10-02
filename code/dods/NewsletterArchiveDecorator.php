<?php

/**
 * This pays allows you to display old newsletters.
 *
 **/

class NewsletterArchiveDecorator extends DataObjectDecorator {

function extraStatics(){
		return array(
			'casting' => array(
				"ViewingPage" => "SiteTree",
				"Link" => "Varchar"
			),
		);
	}

	/**
	 * Update the CMS fields specifically for Member
	 * decorated by this NewsletterRole decorator.
	 *
	 * @param FieldSet $fields CMS fields to update
	 */
	function updateCMSFields($fields) {
		if($link = $this->getLink()){
			$fields->addFieldToTab("Root.Newsletter", new LiteralField("LinkLink", "<h3><a href=\"$link\">view online link</a></h3>"));
		}
		else {
			$fields->addFieldToTab("Root.Newsletter", new LiteralField("LinkLink", "<p>There is no preview link, you need to setup a Newsletter Archive Page specifically for this newsletter.</p>"));
		}
	}

	function getViewingPage() {
		return $this->owner->ViewingPage();
	}

	function ViewingPage() {
		if($this->owner->SentDate) {
			if($this->owner->ParentID) {
				$parent = DataObject::get_by_id("NewsletterType", $this->owner->ParentID);
				if($parent) {
					return DataObject::get_one("NewsletterArchivePage", "NewsletterTypeID = ".$parent->ID);
				}
			}
		}
	}

	function Link()  { return $this->getLink();}
	function getLink()  {
		$viewingPage = $this->ViewingPage();
		if($viewingPage) {
			return Director::absoluteBaseURL().$viewingPage->Link("showonenewsletter")."/".$this->owner->ID;
		}
	}

}

