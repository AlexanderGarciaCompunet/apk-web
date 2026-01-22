class ConfigEndpointsAccess {
  static String ipAddress = "192.168.76.84";
  static String pathServerAccess = "http://${ConfigEndpointsAccess.ipAddress}/api/";
  static List<String> listPathServer = [
    "192.168.76.84",
    "192.168.75.66",
    "186.147.143.253",
    "181.49.162.11"

    //  http://${ConfigEndpointsAccess.ip_address}/api/
  ];
  static void modifyServerAccess(String serverAcces) {
    ConfigEndpointsAccess.pathServerAccess = "http://${ConfigEndpointsAccess.ipAddress}/api/";
  }
}
