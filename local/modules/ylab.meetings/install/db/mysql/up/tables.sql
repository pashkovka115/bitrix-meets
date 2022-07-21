CREATE TABLE `y_meetings_room`
(
    `ID`                   INT          NOT NULL AUTO_INCREMENT,
    `NAME`                 VARCHAR(255) NOT NULL,
    `ACTIVITY`             VARCHAR(1)   NOT NULL,
    `INTEGRATION_ID`       INT          NOT NULL,
    `CALENDAR_TYPE_XML_ID` VARCHAR(255) NOT NULL,
    PRIMARY KEY (`ID`),
    CONSTRAINT y_meetings_room_calendar_type_xml_id
        FOREIGN KEY (CALENDAR_TYPE_XML_ID) REFERENCES b_calendar_type(XML_ID)
);

CREATE TABLE `y_meetings_integration`
(
    `ID`              INT          NOT NULL AUTO_INCREMENT,
    `NAME`            VARCHAR(255) NOT NULL,
    `ACTIVITY`        VARCHAR(1)   NOT NULL,
    `INTEGRATION_REF` VARCHAR(255) NOT NULL,
    `LOGIN`           VARCHAR(255) NOT NULL,
    `PASSWORD`        VARCHAR(255) NOT NULL,
    PRIMARY KEY (`ID`)
)