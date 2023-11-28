import 'package:flutter/material.dart';
import 'package:progress_dialog_null_safe/progress_dialog_null_safe.dart';

class ProgressDialogComponent {
  late ProgressDialog _progressDialog;

  ProgressDialogComponent(BuildContext context, String message) {
    _progressDialog = ProgressDialog(context);
    _progressDialog.style(
      message: message,
      progressWidget: const CircularProgressIndicator(),
    );
  }

  void show() {
    _progressDialog.show();
  }

  void hide() {
    _progressDialog.hide();
  }
}
