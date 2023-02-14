@extends('emails.layout', ['title' => 'Підтвердження реєстрації'])

@section('content')
  <span class="preheader">Підтвердіть ваш email.</span>
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
                      <p>Вітаємо!</p>
                      <p>
                        Нещодавно ви зареєструвались на сайті terevenky.com.
                      </p>
                      <p>Щоб завершити реєстрацію, нам необхідно, щоб ви підтвердили ваш email.</p>
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
                                  <a href="https://terevenky.com/verify-email?t={{ $hashedId }}" target="_blank">
                                    Підтвердити Email
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