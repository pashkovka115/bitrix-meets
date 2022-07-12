CREATE TABLE `y_meetings_room`
(
    `ID`             int          NOT NULL AUTO_INCREMENT,
    `NAME`           varchar(255) NOT NULL,
    `ACTIVITY`       varchar(1)   NOT NULL,
    `INTEGRATION_ID` int          NOT NULL,
    PRIMARY KEY (`ID`)
);

CREATE TABLE `y_meetings_integration`
(
    `ID`              int          NOT NULL AUTO_INCREMENT,
    `NAME`            varchar(255) NOT NULL,
    `ACTIVITY`        varchar(1)   NOT NULL,
    `INTEGRATION_REF` varchar(255) NOT NULL,
    `LOGIN`           varchar(255) NOT NULL,
    `PASSWORD`        varchar(255) NOT NULL,
    PRIMARY KEY (`ID`)
)