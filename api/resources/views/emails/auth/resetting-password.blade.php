@extends('emails.layout', ['title' => 'Відновлення паролю.'])

@section('content')
  <span class="preheader">Відновлення паролю.</span>
  <table role="presentation" border="0" cellpadding="0" cellspacing="0" class="body">
    <tr>
      <td>&nbsp;</td>
      <td class="container">
        <div class="content">

          <!-- START CENTERED WHITE CONTAINER -->
          <table role="presentation" class="main">

            <!-- START MAIN CONTENT AREA -->
            <tr>
              <td class="wrapper">
                <table role="presentation" border="0" cellpadding="0" cellspacing="0">
                  <tr>
                    <td>
                      <p>Вітаємо, {{ $login }}!</p>
                      <p>Щоб відновити свій пароль, нажміть на кнопку.</p>
                      <table role="presentation" border="0" cellpadding="0" cellspacing="0"
                             class="btn btn-primary">
                        <tbody>
                        <tr>
                          <td align="left">
                            <table role="presentation" border="0" cellpadding="0"
                                   cellspacing="0">
                              <tbody>
                              <tr>
                                <td>
                                  <a href="{{ $actionUrl }}" target="_blank">
                                    Відновити пароль
                                  </a>
                                </td>
                              </tr>
                              </tbody>
                            </table>
                          </td>
                        </tr>
                        </tbody>
                      </table>
                      <p>Бажаємо гарного дня! Команда <a href="https://terevenky.com">terevenky.com</a></p>
                      <p>
                        Як що кнопка не працює, ось пряме посилання: <a href="{{ $actionUrl }}">{{ $actionUrl }}</a>
                      </p>
                    </td>
                  </tr>
                </table>
              </td>
            </tr>

            <!-- END MAIN CONTENT AREA -->
          </table>
          <!-- END CENTERED WHITE CONTAINER -->

          <!-- START FOOTER -->
          {{--<div class="footer">--}}
          {{--  <table role="presentation" border="0" cellpadding="0" cellspacing="0">--}}
          {{--    <tr>--}}
          {{--      <td class="content-block">--}}
          {{--        <span class="apple-link">Company Inc, 3 Abbey Road, San Francisco CA 94102</span>--}}
          {{--        <br> Don't like these emails? <a href="http://i.imgur.com/CScmqnj.gif">Unsubscribe</a>.--}}
          {{--      </td>--}}
          {{--    </tr>--}}
          {{--    <tr>--}}
          {{--      <td class="content-block powered-by">--}}
          {{--        Powered by <a href="http://htmlemail.io">HTMLemail</a>.--}}
          {{--      </td>--}}
          {{--    </tr>--}}
          {{--  </table>--}}
          {{--</div>--}}
          <!-- END FOOTER -->

        </div>
      </td>
      <td>&nbsp;</td>
    </tr>
  </table>
@endsection