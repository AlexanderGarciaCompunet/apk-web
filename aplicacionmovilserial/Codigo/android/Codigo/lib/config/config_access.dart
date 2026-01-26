class ConfigEndpointsAccess {
  static String ipAddress = "10.240.76.13";
  static String pathServerAccess = "http://${ConfigEndpointsAccess.ipAddress}/api/";
  static List<String> listPathServer = [
    "10.240.76.13",
    "190.216.201.61",
    "172.16.101.13",
    "192.168.76.84",
    "186.147.143.253",
    "181.49.162.11"

    //  http://${ConfigEndpointsAccess.ip_address}/api/
  ];
  static void modifyServerAccess(String serverAcces) {
    ConfigEndpointsAccess.pathServerAccess = "http://${ConfigEndpointsAccess.ipAddress}/api/";
  }
}
