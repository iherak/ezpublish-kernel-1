<?php
/**
 * File containing the ObjectState Gateway class
 *
 * @copyright Copyright (C) 1999-2012 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 */

namespace eZ\Publish\Core\Persistence\Legacy\Content\ObjectState;

use eZ\Publish\SPI\Persistence\Content\ObjectState,
    eZ\Publish\SPI\Persistence\Content\ObjectState\Group;

/**
 * ObjectState Gateway
 */
abstract class Gateway
{
    /**
     * Loads data for an object state
     *
     * @param mixed $stateId
     * @return array
     */
    abstract public function loadObjectStateData( $stateId );

    /**
     * Loads data for all object states belonging to group with $groupId ID
     *
     * @param mixed $groupId
     * @return array
     */
    abstract public function loadObjectStateListData( $groupId );

    /**
     * Loads data for an object state group
     *
     * @param mixed $groupId
     * @return array
     */
    abstract public function loadObjectStateGroupData( $groupId );

    /**
     * Loads data for all object state groups, filtered by $offset and $limit
     *
     * @param int $offset
     * @param int $limit
     * @return array
     */
    abstract public function loadObjectStateGroupListData( $offset, $limit );

    /**
     * Inserts a new object state into database
     *
     * @param \eZ\Publish\SPI\Persistence\Content\ObjectState $objectState
     * @param int $groupId
     */
    abstract public function insertObjectState( ObjectState $objectState, $groupId );

    /**
     * Updates the stored object state with provided data
     *
     * @param \eZ\Publish\SPI\Persistence\Content\ObjectState $objectState
     */
    abstract public function updateObjectState( ObjectState $objectState );

    /**
     * Deletes object state identified by $stateId
     *
     * @param int $stateId
     */
    abstract public function deleteObjectState( $stateId );

    /**
     * Inserts a new object state group into database
     *
     * @param \eZ\Publish\SPI\Persistence\Content\ObjectState\Group $objectStateGroup
     */
    abstract public function insertObjectStateGroup( Group $objectStateGroup );

    /**
     * Updates the stored object state group with provided data
     *
     * @param \eZ\Publish\SPI\Persistence\Content\ObjectState\Group $objectStateGroup
     */
    abstract public function updateObjectStateGroup( Group $objectStateGroup );

    /**
     * Deletes the object state group identified by $groupId
     *
     * @param mixed $groupId
     */
    abstract public function deleteObjectStateGroup( $groupId );

    /**
     * Sets the object state $stateId to content with $contentId ID
     *
     * @param mixed $contentId
     * @param mixed $groupId
     * @param mixed $stateId
     */
    abstract public function setObjectState( $contentId, $groupId, $stateId );

    /**
     * Loads object state data for $contentId content from $stateGroupId state group
     *
     * @param int $contentId
     * @param int $stateGroupId
     *
     * @return array
     */
    abstract public function loadObjectStateDataForContent( $contentId, $stateGroupId );

    /**
     * Returns the number of objects which are in this state
     *
     * @param mixed $stateId
     * @return int
     */
    abstract public function getContentCount( $stateId );

    /**
     * Returns the current priority list in the following format:
     * <code>
     *     array(
     *         1 => 0,
     *         4 => 1,
     *         6 => 2
     *     )
     * </code>
     *
     * where array keys are state IDs and array values are associated priorities
     *
     * @param mixed $groupId
     * @return array
     */
    abstract public function loadCurrentPriorityList( $groupId );

    /**
     * Reorders the priority list inside a state group.
     * Parameters need to be in the following format:
     * <code>
     *     array(
     *         1 => 0,
     *         4 => 1,
     *         6 => 2
     *     )
     * </code>
     *
     * where array keys are state IDs and array values are associated priorities
     *
     * @param array $currentPriorityList
     * @param array $newPriorityList
     */
    abstract public function reorderPriorities( array $currentPriorityList, array $newPriorityList );

    /**
     * Assigns the state with $stateId ID to all content objects
     *
     * @param int $stateId
     */
    abstract public function assignStateToContentObjects( $stateId );
}