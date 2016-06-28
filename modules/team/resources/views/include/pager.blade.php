<?php
use Rikkei\Team\View\Config;
use Rikkei\Core\View\Form;

?>

@if (isset($collectionModel) && $collectionModel->total())
    <?php
        $limit = $page = null;
        if (Form::getFilterPagerData('limit')) {
            $limit = Form::getFilterPagerData('limit');
        } else {
            $limit = $collectionModel->perPage();
        }
        if (Form::getFilterPagerData('page')) {
            $page = Form::getFilterPagerData('page');
        } else {
            $page = $collectionModel->currentPage();
        }
    ?>
    <div class="grid-pager">
        <div class="data-pager-info grid-pager-box" role="status" aria-live="polite">
            <span>{{ trans('team::view.Sum :itemTotal entries / :pagerTotal pager', [
                'itemTotal' => $collectionModel->total(),
                'pagerTotal' => ceil($collectionModel->total() / $collectionModel->perpage()),
                ]) }}</span>
        </div>
        
        <div class="grid-pager-box-right">
        <div class="dataTables_length grid-pager-box">
            <label>{{ trans('team::view.Show') }}
                <select name="limit" class="form-control input-sm">
                    @foreach(Config::toOptionLimit() as $option)
                        <option value="{{ Config::urlParams(['limit' => $option['value']]) }}"<?php 
                            if ($option['value'] == $limit): ?> selected<?php endif; ?>
                        data-value="{{ $option['value'] }}">{{ $option['label'] }}</option>
                    @endforeach
                </select>
            </label>
        </div>

            <div class="dataTables_paginate paging_simple_numbers grid-pager-box pagination-wrapper">
                <ul class="pagination">
                    <li class="paginate_button first-page<?php if($collectionModel->currentPage() == 1): ?> disabled<?php endif; ?>">
                        <a href="<?php if($collectionModel->currentPage() != 1): ?>{{ Config::urlParams(['page' => 1]) }}<?php else: ?>#<?php endif; ?>" data-page="1">
                            <i class="fa fa-angle-double-left"></i>
                        </a>
                    </li>
                    <li class="paginate_button previous<?php if($collectionModel->currentPage() == 1): ?> disabled<?php endif; ?>">
                        <a href="<?php 
                            if($collectionModel->currentPage() != 1): ?>{{ Config::urlParams(['page' => $collectionModel->currentPage()-1]) }}<?php 
                            else: ?>#<?php endif; ?>" data-page="{{ $collectionModel->currentPage()-1 }}">
                            <i class="fa fa-arrow-left"></i>
                        </a>
                    </li>
                    <li class="paginate_button">
                        <form action="{{ Config::urlParams(['page' => null]) }}" method="get" class="form-pager">
                            <input class="input-text form-control" name="page" value="{{ $page }}" />
                        </form>
                    </li>
                    <li class="paginate_button next<?php if(!$collectionModel->hasMorePages()): ?> disabled<?php endif; ?>">
                        <a href="<?php 
                            if($collectionModel->hasMorePages()): ?>{{ Config::urlParams(['page' => $collectionModel->currentPage()+1]) }}<?php
                            else: ?>#<?php endif; ?>" data-page="{{ $collectionModel->currentPage()+1 }}">
                            <i class="fa fa-arrow-right"></i>
                        </a>
                    </li>
                    <li class="paginate_button lastpage-page<?php if($collectionModel->lastPage() == $collectionModel->currentPage()): ?> disabled<?php endif; ?>">
                        <a href="<?php 
                            if($collectionModel->lastPage() != $collectionModel->currentPage()): ?>{{ Config::urlParams(['page' => $collectionModel->lastPage()]) }}<?php
                            else: ?>#<?php endif; ?>" data-page="{{ $collectionModel->lastPage() }}">
                            <i class="fa fa-angle-double-right"></i>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="clearfix"></div>
    </div>
@endif