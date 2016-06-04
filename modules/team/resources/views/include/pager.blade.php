<?php
use Rikkei\Team\View\Config;
?>

@if (isset($collectionModel) && $collectionModel->total())
    <div class="grid-pager">
        <div class="data-pager-info grid-pager-box" role="status" aria-live="polite">
            <span>{{ trans('team::view.Showing :itemCount of :itemTotal entries / :pagerTotal pager', [
                'itemCount' => $collectionModel->count(),
                'itemTotal' => $collectionModel->total(),
                'pagerTotal' => ceil($collectionModel->total() / $collectionModel->perpage()),
                ]) }}</span>
        </div>

        <div class="dataTables_length grid-pager-box">
            <label>{{ trans('team::view.Show') }}
                <select name="limit" class="form-control input-sm" onchange="window.location.href = this.value;">
                    @foreach(Config::toOptionLimit() as $option)
                        <option value="{{ Config::urlParams(['limit' => $option['value']]) }}"<?php 
                            if ($option['value'] == $collectionModel->perPage()): ?> selected<?php endif; ?>
                        >{{ $option['label'] }}</option>
                    @endforeach
                </select>
            </label>
        </div>

        <div class="dataTables_paginate paging_simple_numbers grid-pager-box">
            <ul class="pagination">
                <li class="paginate_button first-page<?php if($collectionModel->currentPage() == 1): ?> disabled<?php endif; ?>">
                    <a href="<?php if($collectionModel->currentPage() != 1): ?>{{ Config::urlParams(['page' => 1]) }}<?php else: ?>#<?php endif; ?>">
                        <i class="fa fa-angle-double-left"></i>
                    </a>
                </li>
                <li class="paginate_button previous<?php if($collectionModel->currentPage() == 1): ?> disabled<?php endif; ?>">
                    <a href="<?php 
                        if($collectionModel->currentPage() != 1): ?>{{ Config::urlParams(['page' => $collectionModel->currentPage()-1]) }}<?php 
                        else: ?>#<?php endif; ?>">
                        <i class="fa fa-arrow-left"></i>
                    </a>
                </li>
                <li class="paginate_button">
                    <form action="{{ Config::urlParams(['page' => null]) }}" method="get" class="form-pager">
                        <input class="input-text form-control" name="page" value="{{ $collectionModel->currentPage() }}" />
                    </form>
                </li>
                <li class="paginate_button next<?php if(!$collectionModel->hasMorePages()): ?> disabled<?php endif; ?>">
                    <a href="<?php 
                        if($collectionModel->hasMorePages()): ?>{{ Config::urlParams(['page' => $collectionModel->currentPage()+1]) }}<?php
                        else: ?>#<?php endif; ?>">
                        <i class="fa fa-arrow-right"></i>
                    </a>
                </li>
                <li class="paginate_button lastpage-page<?php if($collectionModel->lastPage() == $collectionModel->currentPage()): ?> disabled<?php endif; ?>">
                    <a href="<?php 
                        if($collectionModel->lastPage() != $collectionModel->currentPage()): ?>{{ Config::urlParams(['page' => $collectionModel->lastPage()]) }}<?php
                        else: ?>#<?php endif; ?>">
                        <i class="fa fa-angle-double-right"></i>
                    </a>
                </li>
            </ul>
        </div>
        <div class="clearfix"></div>
    </div>
@endif