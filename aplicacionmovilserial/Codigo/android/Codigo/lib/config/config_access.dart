class ConfigEndpointsAccess {
  static String ipAddress = "201.234.153.155";
  static String pathServerAccess = "https://myscanuma.grupocnet.com/api/";
  static List<String> listPathServer = [
    "myscanuma.grupocnet.com (201.234.153.155)"
  ];
  static void modifyServerAccess(String serverAcces) {
    ConfigEndpointsAccess.pathServerAccess = "https://myscanuma.grupocnet.com/api/";
  }
}
