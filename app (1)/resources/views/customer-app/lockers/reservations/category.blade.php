<div class="form-group form-md-line-input ">
    <select  class="form-control" name="price_type" id="price_type">
        <option value="">Select Price </option>
        @if($category->price > 0)
        <option value="1" data-price="{{ $category->price }}">1 Month Price: NPR {{ $category->price }} </option>
        @endif
        @if($category->three_month_price > 0)
        <option value="3" data-price="{{ $category->three_month_price }}">3 Month Price: NPR {{ $category->three_month_price }} </option>
        @endif
        @if($category->six_month_price > 0)
        <option value="6" data-price="{{ $category->six_month_price }}">6 Month Price: NPR {{ $category->six_month_price }} </option>
        @endif
        @if($category->one_year_price > 0)
        <option value="12" data-price="{{ $category->one_year_price }}">1 Year Price: NPR {{ $category->one_year_price }} </option>
        @endif
    </select>
    <label for="title">Select Price Variations</label>
    <span class="help-block"></span>
</div>
