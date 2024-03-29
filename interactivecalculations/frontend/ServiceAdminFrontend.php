<?php
/*
 *
 *   This file is part of the 'iCalc - Interactive Calculations' project.
 *
 *   Copyright (C) 2023, Jakub Jandák
 *
 *   This program is free software: you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation, either version 3 of the License, or
 *   (at your option) any later version.
 *
 *   This program is distributed in the hope that it will be useful,
 *   but WITHOUT ANY WARRANTY; without even the implied warranty of
 *   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 *   GNU General Public License for more details.
 *
 *   You should have received a copy of the GNU General Public License
 *   along with this program. If not, see <https://www.gnu.org/licenses/>.
 *
 *
 */

namespace interactivecalculations\fe;

use interactivecalculations\db\model\Service;
use interactivecalculations\fe\displayTypes\ChooseList;
use interactivecalculations\fe\displayTypes\DisplayTypeManager;

class ServiceAdminFrontend extends AbstractAdminFrontend
{

    public static function configuration()
    {
        self::populateinteractivecalculationsJSData();

        $data = Service::get_all_with_unit();


        if (is_null($data)) {
            error_log("ERROR Fetching data from API");
        }
        $tbody = "";
        $html = "";
        foreach ($data as $item) {
            $modalId = "service" . $item["id"] . "modal";
            $modalData = [];
            $modalData["name"] = $item["name"];
            $modalData["desc"] = $item["description"];
            $modalData["price"] = $item["price"];
            $modalData["unit"] = $item["unit"];
            $modalData["min_quantity"] = $item["min_quantity"];
            $modalData["display_type"] = $item["display_type"];


            $html = $html . self::configuredModalEdit($modalId, $item["id"], $modalData);


            $tbody = $tbody . '
            <tr>
                    <td>' . $item["id"] . '</td>
                    <td>' . $item["name"] . '</td>
                    <td>' . $item["description"] . '</td>
                    <td>' . $item["price"] . '</td>
                    <td>' . $item["unit"] . '</td>
                    <td>' . $item["min_quantity"] . '</td>
                    <td>' . $item["display_type"] . '</td>
                    <td class="text-center"><button class="btn btn-info" data-toggle="modal" data-target="#' . $modalId . '"><span class="dashicons dashicons-edit"></span></button></td>
                    <td class="text-center"><button class="btn btn-danger" onclick="interactivecalculations_process_service_deletion(' . $item["id"] . ',\'' . $item["name"] . '\')"><span class="dashicons dashicons-trash"></span></button></td>
                </tr>';
        }

        $serviceCreationModal = "serviceCreationModal";

        $html = $html . self::configureCreationModal($serviceCreationModal);
        $html = $html . '
    <div class="container pt-5">
        <!-- Additon button -->
        <span><button class="button mb-2" data-toggle="modal" data-target="#' . $serviceCreationModal . '">+</button> ' . esc_html(__("Add New Service")) . '</span>
            <!-- Table -->
            <table class="table table-bordered table-striped table-hover col-12">
                <thead class="thead-dark">
                    <tr class="col-12">
                        <th class="p-2 m-2">' . esc_html(__("ID")) . '</th>
                        <th class="p-2 m-2">' . esc_html(__("Name")) . '</th>
                        <th class="p-2 m-2">' . esc_html(__("Description")) . '</th>
                        <th class="p-2 m-2">' . esc_html(__("Price per Unit")) . '</th>
                        <th class="p-2 m-2">' . esc_html(__("Unit")) . '</th>
                        <th class="p-2 m-2">' . esc_html(__("Minimal Quantity")) . '</th>
                        <th class="p-2 m-2">' . esc_html(__("Display Type")) . '</th>
                        <th class="col-1"></th>
                        <th class="col-1"></th>
                    </tr>
                </thead>
                <tbody id="table-body">
                ' . $tbody . '
                </tbody>
            </table>
        <!-- Pagination -->
        <div class="wp-block-navigation">
            <!-- Add pagination links here -->
        </div>
    </div>';

        echo $html;
    }

    public static function configuredModalEdit($modalId, $id, $formFields): string
    {
        $displayTypeList = new ChooseList();
        $displayTypeList->directConfiguration($modalId . "_display_type_form", "display_type", "form-control", DisplayTypeManager::getAllDisplayTypesForProductAndService(), $formFields['display_type']);


        return '<div class="modal mt-5 fade w-100 p-3" id="' . $modalId . '" role="dialog">
                        <div class="modal-dialog modal-lg">
                          <div class="modal-content">
                            <div class="modal-header">
                              <button type="button" class="close" data-dismiss="modal">&times;</button>
                            </div>
                            <div class="modal-body p-5">
                              <h4 class="modal-title">' . esc_html(__("Edit Service")) . '</h4>
                              <form id="' . $modalId . '_form">
                              <div class="form-row interactivecalculations-service-form-row">
                                <div class="col interactivecalculations-edit-table-space-between">
                                  <label for="' . $modalId . '_id_form">' . esc_html(__("ID")) . '</label>
                                  <input id="' . $modalId . '_id_form" type="text" class="form-control" value="' . $id . '" readonly>
                                </div>
                                <div class="col interactivecalculations-edit-table-space-between">
                                  <label for="' . $modalId . '_name_form">' . esc_html(__("Name")) . '</label>
                                  <input id="' . $modalId . '_name_form" type="text" class="form-control" placeholder="' . esc_html(__("Name")) . '" value="' . $formFields['name'] . '">
                                </div>
                                 <div class="col interactivecalculations-edit-table-space-between">
                                  <label for="' . $modalId . '_desc_form">' . esc_html(__("Description")) . '</label>
                                  <input id="' . $modalId . '_desc_form" type="text" class="form-control" placeholder="' . esc_html(__("Description")) . '" value="' . $formFields['desc'] . '">
                                </div>
                                <div class="col interactivecalculations-edit-table-space-between">
                                  <label for="' . $modalId . '_price_form">' . esc_html(__("Price per Unit")) . '</label>
                                  <input id="' . $modalId . '_price_form" type="text" class="form-control" placeholder="' . esc_html(__("Price per Unit")) . '" value="' . $formFields['price'] . '">
                                </div>
                                <div class="col interactivecalculations-edit-table-space-between">
                                  <label for="' . $modalId . '_unit_form">' . esc_html(__("Unit")) . '</label>
                                  <input id="' . $modalId . '_unit_form" type="text" class="form-control" placeholder="' . esc_html(__("Unit")) . '" value="' . $formFields['unit'] . '">
                                </div> 
                                <div class="col interactivecalculations-edit-table-space-between">
                                  <label for="' . $modalId . '_min_quantity_form">' . esc_html(__("Minimal Quantity")) . '</label>
                                  <input id="' . $modalId . '_min_quantity_form" type="text" class="form-control" placeholder="' . esc_html(__("Minimal Quantity")) . '" value="' . $formFields['min_quantity'] . '">
                                </div>
                                 <div class="col interactivecalculations-edit-table-space-between">
                                  <label for="' . $modalId . '_display_type_form">' . esc_html(__("Display Type")) . '</label>
                                    ' .
            $displayTypeList->render()
            . '
                              </div>
                              </div>
                                <div class="d-flex justify-content-end">
                                </div>
                            </form>
                            </div>
                            <div class="modal-footer">
                            
                              <button type="button" class="btn btn-danger mr-2" data-dismiss="modal">' . esc_html(__("Close")) . '</button>
                              <button type="button" class="btn btn-primary"  data-dismiss="modal" onclick="interactivecalculations_process_service_edition(\'' . $id . '\',\'' . $modalId . '\')">' . esc_html(__("Edit")) . '</button>
                            </div>
                          </div>
                        </div>
                      </div>';
    }

    public static function configureCreationModal($modalId): string
    {
        $displayTypeList = new ChooseList();
        $displayTypeList->directConfiguration($modalId . "_display_type_form", "display_type", "form-control", DisplayTypeManager::getAllDisplayTypesForProductAndService());

        return '<div class="modal mt-5 fade w-100 p-3" id="' . $modalId . '"  role="dialog">
                        <div class="modal-dialog modal-lg">
                          <div class="modal-content">
                            <div class="modal-header">
                              <button type="button" class="close" data-dismiss="modal">&times;</button>
                            </div>
                            <div class="modal-body p-5">
                              <h4 class="modal-title">' . esc_html(__("Create New Service")) . '</h4>
                              <form id="' . $modalId . '_form">
                              <div class="form-row interactivecalculations-service-form-row">
                                <div class="col interactivecalculations-edit-table-space-between">
                                  <label for="' . $modalId . '_name_form">' . esc_html(__("Name")) . '</label>
                                  <input id="' . $modalId . '_name_form" type="text" class="form-control" placeholder="' . esc_html(__("Service Name")) . '">
                                </div>
                                 <div class="col interactivecalculations-edit-table-space-between">
                                  <label for="' . $modalId . '_desc_form">' . esc_html(__("Description")) . '</label>
                                  <input id="' . $modalId . '_desc_form" type="text" class="form-control" placeholder="' . esc_html(__("Service Description")) . '" >
                                </div>
                                <div class="col interactivecalculations-edit-table-space-between">
                                  <label for="' . $modalId . '_price_form">' . esc_html(__("Price per Unit")) . '</label>
                                  <input id="' . $modalId . '_price_form" type="number" class="form-control" placeholder="' . esc_html(__("Service Price")) . '" >
                                </div>
                                <div class="col interactivecalculations-edit-table-space-between">
                                  <label for="' . $modalId . '_unit_form">' . esc_html(__("Unit")) . '</label>
                                  <input id="' . $modalId . '_unit_form" type="text" class="form-control" placeholder="' . esc_html(__("Service Unit")) . '" >
                                </div>
                                <div class="col interactivecalculations-edit-table-space-between">
                                  <label for="' . $modalId . '_min_quantity_form">' . esc_html(__("Minimal Quantity")) . '</label>
                                  <input id="' . $modalId . '_min_quantity_form" type="number" class="form-control" placeholder="' . esc_html(__("Service Minimal Quantity")) . '" >
                                </div>
                                 <div class="col interactivecalculations-edit-table-space-between">
                                  <label for="' . $modalId . '_display_type_form">' . esc_html(__("Display Type")) . '</label>
                                                                   ' .
            $displayTypeList->render()
            . '
                                </div>
                              </div>
                            <div class="d-flex justify-content-end">
                            </div>
                            </form>
                            </div>
                            <div class="modal-footer">
                              <button type="button" class="btn btn-danger mr-2" data-dismiss="modal">' . esc_html(__("Close")) . '</button>
                              <button type="button" class="btn btn-primary"  data-dismiss="modal" onclick="interactivecalculations_process_service_creation(\'' . $modalId . '\')">' . esc_html(__("Save")) . '</button>
                            </div>
                          </div>
                        </div>
                      </div>';
    }


}