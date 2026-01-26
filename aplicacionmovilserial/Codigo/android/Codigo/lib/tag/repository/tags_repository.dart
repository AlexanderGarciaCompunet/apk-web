import 'package:almaviva_integration/tag/repository/tags_server_api.dart';

import 'package:http/http.dart' as http;

class TagRepository {
  final _tagServerApi = TagsServerApi();

  Future<http.Response> tagListServerRepository(int itemId, int customerId) =>
      _tagServerApi.tagsServerApi(itemId, customerId);
}
