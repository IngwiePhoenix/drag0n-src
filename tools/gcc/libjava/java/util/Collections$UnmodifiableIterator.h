
// DO NOT EDIT THIS FILE - it is machine generated -*- c++ -*-

#ifndef __java_util_Collections$UnmodifiableIterator__
#define __java_util_Collections$UnmodifiableIterator__

#pragma interface

#include <java/lang/Object.h>

class java::util::Collections$UnmodifiableIterator : public ::java::lang::Object
{

public: // actually package-private
  Collections$UnmodifiableIterator(::java::util::Iterator *);
public:
  virtual ::java::lang::Object * next();
  virtual jboolean hasNext();
  virtual void remove();
private:
  ::java::util::Iterator * __attribute__((aligned(__alignof__( ::java::lang::Object)))) i;
public:
  static ::java::lang::Class class$;
};

#endif // __java_util_Collections$UnmodifiableIterator__