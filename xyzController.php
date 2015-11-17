<?php
namespace Cab\CabagCalendar\Controller;

use \TYPO3\CMS\Core\Utility\GeneralUtility;

/***************************************************************
 *
 *  Copyright notice
 *
 *  (c) 2015 Xiaoling Peng <xp@cabag.ch>, cab services ag
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

/**
 * CalendarController
 */

class CalendarController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController {

	/**
	 * categoryRepository
	 *
	 * @var \Cab\CabagCalendar\Domain\Repository\CategoryRepository
	 * @inject
	 */
	protected $categoryRepository = NULL;
    
    /**
	 * eventRepository
	 *
	 * @var \Cab\CabagCalendar\Domain\Repository\EventRepository
	 * @inject
	 */
	protected $eventRepository = NULL;

	/**
	 * The calendar object.
	 *
	 * @var \Cab\CabagCalendar\Utility\Calendar The calendar object.
	 */
	protected $calendar = null;

	/**
	 * Injects the calendar utility.
	 *
	 * @param \Cab\CabagCalendar\Utility\Calendar $calendar The calendar utility.
	 */
	public function injectCalendar(\Cab\CabagCalendar\Utility\Calendar $calendar = null) {
	    $this->calendar = $calendar;
	}

	/**
	 * action list
	 *
	 * @return void
	 */
	public function listAction() {
	
        // get year from plugin
        $year = $this->settings['year'];
        // get category from plugin
        $categoryUid = $this->settings['category'];
        if($categoryUid == -1) {
        // get events of all categories: do not filter for category
            $categoryUid = null;
        }
        // get events
        $events = $this->eventRepository->findByYearAndCategory($year, $categoryUid);
        // add events to calendar data of given year
        $calendarData = $this->calendar->getData($year, $events, 3);


        //$this->view->assign('setCategory', $category);
        $this->view->assign('calendar', $calendarData);
        $this->view->assign('events', $events);

	}
    
    /**
     * @param integer $uid
     * 
     * @return void
     */
    public function showAction($uid = NULL) {
        if($this->request->hasArgument('event')) {
            $uid = $this->request->getArgument('event');
        }
        $event = $this->eventRepository->findByUid($uid);
        $this->view->assign('event', $event);
        $this->view->assign('today', new \DateTime());
        
        $content = $this->ical_escape_text($this->view->render());
        return $content;
    }
    
    private function ical_escape_text($text) {
        // Here I transform some HTML to keep parts of the visual :
        $text = str_replace("&nbsp;", " ", $text); // Must be replaced before escaping ";" :D
        $text = str_replace("<br>", "\n", $text);
        $text = str_replace("<br />", "\n", $text);
        //$text = str_replace("<p>", "\n", $text);
        $text = str_replace('<p class="bodytext">', "", $text);
        $text = str_replace("</p>", "", $text);
        $text = str_replace("<ul>", " ", $text);
        $text = str_replace("</ul>", " ", $text);
        $text = str_replace("<ol>", " ", $text);
        $text = str_replace("</ol>", " ", $text);
        $text = str_replace("</li>", " ", $text);
        $text = str_replace("<li>", "\t * ", $text); // The list becomes a point form with a tab
        
        //$text = preg_replace('/([\,;])/', '\\\$1', $text);
        
        return $text;
    }
}
