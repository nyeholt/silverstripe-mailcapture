<?php

// This module takes over mailer settings; to enable outbound sends, add the following
// CaptureMailer::$outbound_send = true

// to stop capturing emails, add 
// CaptureMailer::$capture_emails = false

Email::set_mailer(new CaptureMailer());