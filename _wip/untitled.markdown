---
layout: post
title: "(Untitled)"
---

```
Error: Error applying plan:

1 error(s) occurred:

* module.devops.alicloud_db_instance.artifactory_db_instance: 1 error(s) occurred:

* alicloud_db_instance.artifactory_db_instance: [ERROR] terraform-provider-alicloud/alicloud/resource_alicloud_db_instance.go:290: Resource  CreateDBInstance Failed!!! [SDK alibaba-cloud-sdk-go ERROR]:
SDK.ServerError
ErrorCode: OperationDenied
Recommend:
RequestId: 336A000D-AEBF-4D12-8396-FE58C9FDFD9D
Message: The resource is out of usage.

Terraform does not automatically rollback in the face of errors.
Instead, your Terraform state file has been partially updated with
any resources that successfully completed. Please address the error
above and apply again to incrementally change your infrastructure.
```