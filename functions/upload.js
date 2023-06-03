export async function onRequestPost(ctx) {
  const formData = await ctx.request.formData()
  const file = formData.get('file')
  const allowedExtensions = ['jpg', 'gif', 'jpeg', 'png'];
  const fileExtension = file.name.split('.').at(-1)?.toLowerCase() || '';
  if (!allowedExtensions.includes(fileExtension)) {
    return returnJSON({ code: 1, msg: '上传格式不支持' });
  }
  const path = `image/${file.name}`;
  // return new Response(file.constructor)
  console.log(path)
  await ctx.env.static.put(path, file);
  return returnJSON({ code: 0, msg: `https://static.zjm.im/${path}` });
  // return new Response()
  // const obj = await ctx.env.BUCKET.get('some-key');
  // if (obj === null) {
    // return new Response('Not found', { status: 404 });
  // }
  // return new Response(obj.body);
}
export async function onRequest(_ctx) {
  return new Response('405 Method Not Allowed', { status: 405 });
}
function returnJSON(data) {
  return new Response(JSON.stringify(data), {
    headers: {
      'content-type': 'application/json;charset=UTF-8',
    },
  })
}