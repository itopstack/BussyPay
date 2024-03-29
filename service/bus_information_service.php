<?php

require __DIR__ . '/../helper/json_response_parser.php';
require __DIR__ . '/../helper/database_connection.php';
require __DIR__ . '/shared_service.php';

class BusInformationService extends SharedService {

    function getAllBuses(): string {
        $con = DatabaseConnection::getInstance();

        $stmt = $con->prepare("SELECT * FROM buses WHERE id != 99");
        $stmt->execute();
        $result = $stmt->fetchAll();

        return JSONResponseParser::parse($result, 'Success', 'No buses found.', parent::countRecords('buses'));
    }

    function search(string $keyword): string {
        $con = DatabaseConnection::getInstance();
        $keyword = trim($keyword);

        $stmt = $con->prepare("SELECT * FROM buses WHERE id != 99 AND (bus_name LIKE :bus_name 
                                    OR source LIKE :source OR terminal LIKE :terminal OR id = :id)");

        $stmt->bindValue(':id', $keyword);
        $stmt->bindValue(':bus_name', '%' . $keyword . '%');
        $stmt->bindValue(':source', '%' . $keyword . '%');
        $stmt->bindValue(':terminal', '%' . $keyword . '%');

        $stmt->execute();
        $result = $stmt->fetchAll();

        return JSONResponseParser::parse($result, 'Success', 'No buses found.', parent::countRecords('buses'));
    }

}