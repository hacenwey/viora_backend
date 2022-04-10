<div class="light-layout">
    <div class="container">
        <section class="small-section border-section border-top-0">
            <div class="row">
                <div class="col-lg-6">
                    <div class="subscribe">
                        <div>
                            <h4>{{ settings()->get('newsletter_title') }}</h4>
                            <p>{{ settings()->get('newsletter_desc') }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <form action="{{ route('backend.subscribe') }}"
                        class="form-inline subscribe-form auth-form needs-validation" method="post"
                        id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form">
                        @csrf
                        <div class="form-group mx-sm-3">
                            <input type="email" class="form-control" value="{{ old('email') }}" name="email" id="mce-EMAIL" placeholder="{{ trans('global.enter_your_email') }}" required="required">
                        </div>
                        <button type="submit" class="btn btn-solid" id="mc-submit">{{ trans('global.subscribe') }}</button>
                    </form>
                </div>
            </div>
        </section>
    </div>
</div>
