

# Holiday
判断节假日，调用第三方api，本地存储

* 在需要控制器中 use 该 trait

```php
namespace Wap\Controller;

// 引用假期功能的命名空间
use Common\Traits as Traits;

use Think\Controller;

class IndexController extends Controller
{

    // 引入方法
    use Traits\Holiday;

    public function index()
    {
        // 查看节假日
        echo $this->chekcMonth();
    }

}
```

### ==注：==使用的免费接口，可能导致程序请求失败，如失效请考虑其他接口：http://timor.tech/api/holiday/
