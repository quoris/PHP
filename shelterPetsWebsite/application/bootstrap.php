<?php

/**
���������� �������� ����������, ���������� ��� ������ (�����������).
 */
session_start();
require_once 'core/model.php';  // ���������� ���� model.php ����
require_once 'core/view.php';   // ���������� ���� view.php ����
require_once 'core/controller.php';     // ���������� ���� controller.php ����
require_once 'core/route.php';          // ���������� ���� � ������� ��������������
Route::start();                         // ��������� �������������

/*
 * � ���� �������� ��� ������, �� ������� �� ����� �������������
 * � ������ ���� ���������� � ������, ������� ����� ������
 * � ������ controlles, core, models.
 */