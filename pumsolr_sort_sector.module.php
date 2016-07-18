<?php
/**
 * @author Jaap Jansma (CiviCooP) <jaap.jansma@civicoop.org>
 * @license http://www.gnu.org/licenses/agpl-3.0.html
 */

function pumsolr_sort_sector_apachesolr_query_prepare(DrupalSolrQueryInterface &$query, &$caller) {
  $sector_tids = _pumsolr_sort_sector_get_user_sector_tids();
  if (count($sector_tids)) {
    $bias = _pumsolr_sort_sector_get_bias();
    $fq = 'im_field_pum_sector:(' . implode(" OR ", $sector_tids) . ')^' . $bias;
    $query->addParam('fl', $fq);
  }
}

function _pumsolr_sort_sector_get_bias() {
  return 21.0;
}

function _pumsolr_sort_sector_get_user_sector_tids() {
  global $user;
  $user_sector_tids = array();

  if ($user->uid > 0) {
    $account = user_load($user->uid);

    if ($account && !empty($account->field_pum_private['und'])) {
      foreach ($account->field_pum_private['und'] as $userterm) {
        $user_sector_tids[] = $userterm['tid'];
      }
    }
    if ($account && !empty($account->field_pum_sector['und'])) {
      foreach ($account->field_pum_sector['und'] as $userterm) {
        $user_sector_tids[] = $userterm['tid'];
      }
    }
  }

  return $user_sector_tids;
}