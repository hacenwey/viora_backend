@extends('frontend.layouts.master')

@section('title', settings()->get('app_name').' | '.trans('global.contact'))

@section('main-content')
	<!-- breadcrumb start -->
    <div class="breadcrumb-section">
        <div class="container">
            <div class="row">
                <div class="col-sm-6">
                    <div class="page-title">
                        <h2>{{ trans('global.contact_us') }}</h2>
                    </div>
                </div>
                <div class="col-sm-6">
                    <nav aria-label="breadcrumb" class="theme-breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('backend.home') }}">{{ trans('global.home') }}</a></li>
                            <li class="breadcrumb-item active">{{ trans('global.contact_us') }}</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    <!-- breadcrumb End -->

    <!--section start-->
    <section class="contact-page section-b-space">
        <div class="container">
            <div class="row section-b-space">
                <div class="col-lg-7 map">
                    @if (settings()->has('maps'))
                        <iframe src="{{ settings('maps') }}" allowfullscreen></iframe>
                    @else
                        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3792.2722858403768!2d-15.984686984889116!3d18.10521298765227!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0xe964d5e334db2d5%3A0xa51f6043d1ed824!2siProd%20Technology!5e0!3m2!1sen!2s!4v1613391448981!5m2!1sen!2s" allowfullscreen></iframe>
                    @endif
                </div>
                <div class="col-lg-5">
                    <div class="contact-right">
                        <ul>
                            <li>
                                <div class="contact-icon"><img src="../assets/images/icon/phone.png"
                                        alt="Generic placeholder image">
                                    <h6>{{ trans('global.contact_us') }}</h6>
                                </div>
                                <div class="media-body">
                                    <p>{{ settings()->get('phone') }}</p>
                                    <p>{{ settings()->get('whatsapp') }}</p>
                                </div>
                            </li>
                            <li>
                                <div class="contact-icon"><i class="fa fa-map-marker" aria-hidden="true"></i>
                                    <h6>{{ trans('global.address') }}</h6>
                                </div>
                                <div class="media-body">
                                    <p>{{ settings()->get('address') }}</p>
                                    {{-- <p>USA 123456</p> --}}
                                </div>
                            </li>
                            <li>
                                <div class="contact-icon"><img src="../assets/images/icon/email.png"
                                        alt="Generic placeholder image">
                                    <h6>{{ trans('global.address_emails') }}</h6>
                                </div>
                                <div class="media-body">
                                    <p>{{ settings()->get('email') }}</p>
                                    {{-- <p>info@shopcart.com</p> --}}
                                </div>
                            </li>
                            {{-- <li>
                                <div class="contact-icon"><i class="fa fa-fax" aria-hidden="true"></i>
                                    <h6>Fax</h6>
                                </div>
                                <div class="media-body">
                                    <p>Support@Shopcart.com</p>
                                    <p>info@shopcart.com</p>
                                </div>
                            </li> --}}
                        </ul>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <form class="theme-form form-contact form contact_form" method="post" action="{{ route('backend.contact.store') }}" id="contactForm" novalidate="novalidate">
                        @csrf
                        <div class="form-row">
                            <div class="col-md-6">
                                <label for="name">{{ trans('global.full_name') }}</label>
                                <input type="text" class="form-control" id="name" name="name" placeholder="{{ trans('global.full_name') }}" required>
                            </div>
                            <div class="col-md-6">
                                <label for="review">{{ trans('global.phone') }}</label>
                                <input type="text" class="form-control" id="review" name="phone" placeholder="{{ trans('global.phone') }}" required>
                            </div>
                            <div class="col-md-6">
                                <label for="email">{{ trans('global.email') }}</label>
                                <input type="text" class="form-control" id="email" name="email" placeholder="{{ trans('global.enter_your_email') }}">
                            </div>
                            <div class="col-md-6">
                                <label for="email">{{ trans('global.subject') }}</label>
                                <input type="text" class="form-control" id="email" name="subject" placeholder="{{ trans('global.subject') }}">
                            </div>
                            <div class="col-md-12">
                                <label for="review">{{ trans('global.message') }}</label>
                                <textarea class="form-control" name="message" placeholder="{{ trans('global.write_message') }}" id="review" rows="6"></textarea>
                            </div>
                            <div class="col-md-12">
                                <button class="btn btn-solid" type="submit">{{ trans('global.send_message') }}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
    <!--Section ends-->


	<!--================Contact Success  =================-->
	<div class="modal fade" id="success" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
		  <div class="modal-content">
			<div class="modal-header">
				<h2 class="text-success">@lang('global.thanks')</h2>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<p class="text-success">@lang('global.send_successfully')</p>
			</div>
		  </div>
		</div>
	</div>

	<!-- Modals error -->
	<div class="modal fade" id="error" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
		  <div class="modal-content">
			<div class="modal-header">
				<h2 class="text-warning">@lang('global.sorry')</h2>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<p class="text-warning">@lang('global.something_went_wrong')</p>
			</div>
		  </div>
		</div>
	</div>
@endsection

@push('styles')
<style>
	/* .modal-dialog .modal-content .modal-header{
		position:initial;
		padding: 10px 20px;
		border-bottom: 1px solid #e9ecef;
	}
	.modal-dialog .modal-content .modal-body{
		height:100px;
		padding:10px 20px;
	}
	.modal-dialog .modal-content {
		width: 50%;
		border-radius: 0;
		margin: auto;
	} */
</style>
@endpush
@push('scripts')
<script src="{{ asset('frontend/js/jquery.form.js') }}"></script>
<script src="{{ asset('frontend/js/jquery.validate.min.js') }}"></script>
<script src="{{ asset('frontend/js/contact.js') }}"></script>
@endpush
